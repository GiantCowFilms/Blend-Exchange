<?php declare(strict_types = 1);

namespace BlendExchange\Flag\Command;
use BlendExchange\Input\IpAddress;
use BlendExchange\Blend\Model\BlendFile;

final class CreateFlag
{
    private $ipAddress;
    private $blend;
    private $offense;
    private $message;

    public function __construct (BlendFile $blend,IpAddress $ipAddress,string $offense,string $message)
    {
        $this->ipAddress = $ipAddress;
        $this->blend = $blend;
        $this->offense = $offense;
        $this->message = $message;
    }

    public function getBlend () {
        return $this->blend;
    }

    public function getIpAddress () {
        return $this->ipAddress->getAnonymized();
    }

    public function getMessage () : string
    {
        return $this->message;
    }

    public function getOffense () : string
    {
        return $this->offense;
    }
}