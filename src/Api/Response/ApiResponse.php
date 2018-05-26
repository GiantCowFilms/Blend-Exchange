<?php declare(strict_types = 1);

namespace BlendExchange\Api\Response;

use BlendExchange\Api\Response;
use Symfony\Component\HttpFoundation;

abstract class ApiResponse implements Response
{
    private $response;

    public function __construct()
    {
        $this->response = $this->getResponse();
    }

    protected function getResponse () {
        return new HttpFoundation\JsonResponse($this->getApiData(), $this->getCode());
    }

    abstract protected function getApiData(): array;

    protected function getCode(): int
    {
        return 200;
    }

    public function send() : void
    {
        $this->response->send();
    }

    public function prepare(HttpFoundation\Request $request) : void
    {
        $this->response->prepare($request);
    }
}
