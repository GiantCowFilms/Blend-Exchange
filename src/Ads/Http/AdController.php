<?php declare(strict_types = 1);

namespace BlendExchange\Ads\Http;

use BlendExchange\Ads\AnimationNodesAd;
use BlendExchange\Ads\FileCountAd;
use BlendExchange\Api\ApiResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdController
{
    private $ads = [
        'AnimationNodesAd' => AnimationNodesAd::class,
        'FileCountAd' => FileCountAd::class
    ];

    public function __construct (
        ApiResponseFactory $api
    ) {
        $this->api = $api;
    }

    public function show ($name,\Stash\Pool $cache) : Response
    {
        if (!isset($this->ads[$name])) {
            return $this->api->errorResponse('Ad not found.',404);
        }
        $ad = new $this->ads[$name]($cache);
        return new StreamedResponse(function () use ($ad) {
            ob_flush();
            header('Content-Type: image/png');
            imagepng($ad->render());
            flush();
        });
    }
}