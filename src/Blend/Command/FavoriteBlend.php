<?php declare(strict_types = 1);

namespace BlendExchange\Blend\Command;
use BlendExchange\Input\IpAddress;

final class FavoriteBlend
{
    private $ipAddress;
    private $blendId;

    public function __construct (string $blendId,IpAddress $ipAddress)
    {
        $this->ipAddress = $ipAddress;
        $this->blendId = $blendId;
    }

    public function getBlendId () {
        return $this->blendId;
    }

    public function getIpAddress () {
        return $this->ipAddress->getAnonymized();
    }
}