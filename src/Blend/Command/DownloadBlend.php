<?php declare(strict_types=1);

namespace BlendExchange\Blend\Command;

final class DownloadBlend
{
    private $blendId;

    public function __construct (string $blendId)
    {
        $this->blendId = $blendId;
    }

    public function getBlendId ()
    {
        return $this->blendId;
    }
}