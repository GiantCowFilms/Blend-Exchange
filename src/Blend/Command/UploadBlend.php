<?php declare(strict_types = 1);

namespace BlendExchange\Blend\Command;
use Psr\Http\Message\StreamInterface;

final class UploadBlend {
    private $handle;
    private $blendId;

    public function __construct (StreamInterface $stream,string $blendId) {
        $this->stream = $stream;
        $this->blendId = $blendId;
    }

    public function getStream() 
    {
        return $this->stream;
    }

    public function getBlendId () : string
    {
        return $this->blendId;
    }
}