<?php declare(strict_types=1);

namespace BlendExchange\Client\StackExchange;

final class StackExchangeClientFactory
{
    private $key;

    public function __construct (string $key) {
        $this->key = $key;
    }

    public function createRegisteredClient() : RegisteredStackExchangeClient
    {
        return new RegisteredStackExchangeClient($this->key);
    }

    public function createAuthenticatedClient(string $accessToken) : AuthenticatedStackExchangeClient
    {
        return new AuthenticatedStackExchangeClient($this->key,$accessToken);
    }
}