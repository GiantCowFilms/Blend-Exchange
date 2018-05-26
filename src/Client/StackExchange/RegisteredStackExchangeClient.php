<?php declare(strict_types=1);

namespace BlendExchange\Client\StackExchange;

use GuzzleHttp\Client;

final class RegisteredStackExchangeClient
{
    private $key;
    private $httpClient;

    public function __construct(string $key) {
        $this->key = $key;
        $this->httpClient = new Client([
            'base_uri' => 'https://api.stackexchange.com/2.2/',
            'headers' => [
                'X-API-Key' => $this->key
            ]
        ]);
    }

    public function getAnswerApi() : AnswerApi
    {
        
    }


}