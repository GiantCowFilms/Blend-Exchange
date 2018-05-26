<?php declare(strict_types=1);

namespace BlendExchange\Api;

use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Manager;
use League\Fractal\Pagination\PaginatorInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use BlendExchange\Authentication\Token\StatelessToken;

use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
final class ApiResponseFactory
{
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    private function resourceToHttpResponse(ResourceInterface $resource, int $code = 200) : Response
    {
        $resourceData = $this->manager->createData($resource)->toArray();
        return new JsonResponse($resourceData, $code);
    }

    public function errorResponse(string $error,int $code = 500)
    {
        return new JsonResponse([
            'type' => 'error',
            'error' => $error,
        ],$code);
    }

    public function validationFailResponse(array $errors, int $code = 422)
    {
        return new JsonResponse([
            'type' => 'error',
            'errors' => $errors,
        ],$code);
    }

    public function requiresModificationResponse(TransformerAbstract $transformer, $item, StatelessToken $token, string $endpoint) : Response
    {
        $resource = new Item($item, $transformer);
        $resourceData = $this->manager->createData($resource)->toArray();
        $resourceData = array_merge($resourceData,[
            'type' => 'requires_modification',
            'token' => 'Bearer '. (string)$token,
            'endpoint' => $endpoint
        ]);
        return  new JsonResponse($resourceData,200);
    }

    public function tokenResponse(TransformerAbstract $transformer, $item, StatelessToken $token) : Response
    {
        $resource = new Item($item, $transformer);
        $resourceData = $this->manager->createData($resource)->toArray();
        $resourceData = array_merge($resourceData,[
            'token' => 'Bearer '. (string)$token
        ]);
        return  new JsonResponse($resourceData,200);
    }

    public function successResponse() {
        return new JsonResponse([
            'type' => 'success'
        ],200);
    }

    public function itemResponse(TransformerAbstract $transformer, $item) : Response
    {
        return $this->resourceToHttpResponse(new Item($item, $transformer));
    }

    public function collectionResponse(TransformerAbstract $transformer, $collection) : Response
    {
        $resource = new Collection($collection, $transformer);
        return $this->resourceToHttpResponse($resource);
    }

    public function paginationResponse(TransformerAbstract $transformer,PaginatorInterface $paginator, $collection) : Response
    {
        $resource = new Collection($collection, $transformer);
        $resource->setPaginator($paginator);
        return $this->resourceToHttpResponse($resource);
    }


    public function streamResponse (StreamInterface $stream) : Response
    {
        $response = new StreamedResponse();
        $response->headers->set('Content-type','application/x-blender');
        $response->setCallback(function () use ($stream) {
            while (!$stream->eof()) {
                echo $stream->read(1024 * 1000);
                ob_flush();
                flush();
            }
        });
        return $response;
    }

    public function notFoundResponse() : Response
    {
        return new JsonResponse([
            'type' => 'error',
            'error' => 'Endpoint was not found.'
        ],404);
    }
}
