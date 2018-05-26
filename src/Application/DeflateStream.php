<?php

// This code is currently a prototype. Has many bugs including
// Incorrect checksum generation (needs to be generated from uncompressed data)
// getSize returns uncompressed size, not compressed size.

namespace BlendExchange\Application;

use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7;

final class DeflateStream implements StreamInterface
{
    private $buffer;
    private $stream;
    private $time;
    private $position;
    private $hash;
    private $size;

    public function __construct(StreamInterface $stream)
    {
        $this->position = 0;
        $resource = Psr7\StreamWrapper::getResource($stream);
        stream_filter_append($resource, 'zlib.deflate', STREAM_FILTER_READ,[
            'level' => 9,
            'window' => 15
        ]);
        $this->stream = new Psr7\Stream($resource);
        $this->time = time();
        $this->hash = hash_init("crc32b");
    }

    public function __toString()
    {
        try {
            return '';
        } catch (\Exception $e) {
            return '';
        }
    }

    public function close()
    {
        $this->stream->close();
    }

    public function detach()
    {
        $this->stream->detach();
    }

    public function getSize () {
        return $this->stream->getSize() + 18;
    }

    public function tell()
    {
        return $this->position;
    }


    public function eof () 
    {
        // account for 8 byte footer
        return $this->stream->eof() && $this->position >= $this->stream->tell() + 8;
    }

    public function isSeekable() {
        return true;
    }

    public function seek($offset, $whence = SEEK_SET) {
        if($whence === SEEK_CUR) {
            $offset += $this->position;
        }
        if($whence === SEEK_END) {
            throw new \RuntimeException('Cannot seek from end');
        }
        $this->stream->seek(max($offset - 10,0));
        $this->position = $offset;
    }

    public function rewind() {
        $this->seek(0);
    }

    public function isWritable() {
        return false;
    }

    public function write($string)
    {
        throw new \RuntimeException('Cannot write to a DeflateStream');
    }

    public function isReadable () {
        return true;
    }

    public function read($length) {
        if ($length < 0) {
            throw new \RuntimeException('Length parameter cannot be negative');
        }

        $data = '';
        // 10 byte header
        if ($this->position < 10) {
            $data .= substr($this->getHeader(),$this->position, $length);
            $this->position += strlen($data);
        }
        $remaining = $length - strlen($data);
        if ($remaining > 0) {
            if (!$this->stream->eof()) {
                $compressed = $this->stream->read($remaining);
                $this->position += strlen($compressed);
                $data .= $compressed;
                hash_update($this->hash,$compressed);
            }
            $remaining = $length - strlen($data);
            if ($this->stream->eof()) {
                // 8 byte footer
                if(!$this->eof()) {
                    $data .= substr($this->getFooter(),$this->position - $this->stream->tell(), $remaining);
                    $this->position += strlen($data);
                }
            }
        }
        return $data;
    }

    public function getContents() {
        throw new \RuntimeException('Cannot get contents');
    }

    public function getMetadata($key = null) {
        return ($key === null) ? null : [];
    }

    private function getHeader () {
        return "\x1F\x8B\x08".pack("V", $this->time)."\0\xFF";
    }

    private function getFooter() {
        // reverse the final has so it is little endian
        $hash =  strrev(hash_final($this->hash,true));

        $size = pack("V",$this->stream->getSize());
        return $hash.$size;
    }
}