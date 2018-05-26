<?php declare(strict_types=1);

abstract class Endpoint
{
    private $httpClient;

    public function __construct(Client $httpClient) 
    {
        $this->httpClient;
    }

    public abstract function execute(Query $query, int $page);
}