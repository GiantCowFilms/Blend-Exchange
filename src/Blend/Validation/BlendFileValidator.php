<?php declare(strict_types = 1);

namespace BlendExchange\Blend\Validation;
use Psr\Http\Message\StreamInterface;


final class BlendFileValidator
{
    public function __construct () {

    }

    public function validate(StreamInterface $stream) : bool
    {
        if(!$stream->isSeekable()) {
            throw new \RuntimeException('Cannot validate a non-seekable stream');
        }
        $result = $stream->read(7) === 'BLENDER';
        $stream->rewind();
        return $result;
    }
}