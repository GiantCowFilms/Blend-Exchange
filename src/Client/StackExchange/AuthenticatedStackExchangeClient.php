<?php declare(strict_types=1);

namespace BlendExchange\Client\StackExchange;

use GuzzleHttp\Client;

final class AuthenticatedStackExchangeClient extends RegisteredStackExchangeClient
{
    private $access_token;

    public function __construct(string $key,string $access_token) {
        $this->key = $key;
        $this->access_token = $access_token;
        $this->httpClient = new Client([
            'base_uri' => 'https://api.stackexchange.com/2.2/',
            'headers' => [
                'X-API-Access-Token' => $this->access_token,
                'X-API-Key' => $this->key
            ]
        ]);
    }
}