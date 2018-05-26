<?php declare(strict_types=1);

namespace BlendExchange\Client\StackExchange\Filter;

final class Filter {

    private $name;
    private $parameters;

    public function __construct (string $name, FilterParameters $parameters) {
        $this->name = $name;
        $this->parameters = $parameters;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getParameters() : FilterParameters
    {
        return $this->parameters;
    }
}