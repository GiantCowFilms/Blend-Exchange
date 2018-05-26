<?php declare(strict_types = 1);

namespace BlendExchange\Input;

final class IpAddress {
    public function __construct (string $ipAddress) {
        $this->ipAddress = $ipAddress;
    }

    public function getAnonymized () : string
    {
        return substr(hash('SHA384',$this->ipAddress),0,512);
    }
}