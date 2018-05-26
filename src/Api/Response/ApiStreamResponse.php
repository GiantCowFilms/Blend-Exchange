<?php declare(strict_types=1);

namespace BlendExchange\Api\Response;

use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ApiStreamResponse extends ApiResponse
{
    private $stream;

    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
        parent::__construct();
    }

    public function getApiData() : array
    {
        return [];
    }

    public function getResponse()
    {
        $response = new StreamedResponse();
        $response->headers->set('Content-type','application/x-blender');
        $response->setCallback(function () {
            while (!$this->stream->eof()) {
                echo $this->stream->read(1024 * 1000);
                ob_flush();
                flush();
            }
        });
        return $response;
    }
}
