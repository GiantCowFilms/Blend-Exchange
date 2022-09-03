<?php declare(strict_types = 1);

namespace BlendExchange\Blend\Validation;
use Psr\Http\Message\StreamInterface;


final class BlendFileValidator
{
    public function __construct () {

    }

    public function validate(StreamInterface $stream) : bool
    {
        // Disable check for now. They changed the compression format in 3.0 and I don't have time
        // to setup the decompression algorithm (which requires installing a php extension)
        return true;
        if(!$stream->isSeekable()) {
            throw new \RuntimeException('Cannot validate a non-seekable stream');
        }
        $stream->rewind();
        $header = $stream->read(7);
        $result = $header === 'BLENDER';
        $stream->rewind();
        return $result;
    }
}