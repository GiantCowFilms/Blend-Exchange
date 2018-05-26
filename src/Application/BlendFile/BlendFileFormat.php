<?php declare(strict_types = 1);

namespace BlendExchange\Application\BlendFile;

use Psr\Http\Message\StreamInterface;
use BlendExchange\Application\DeflateStream;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\InflateStream;
use GuzzleHttp\Psr7\StreamWrapper;

final class BlendFileFormat
{
    private $stream;
    private $endian; //false for reverse order (little endian)
    private $bitSize;
    private $version;

    public function __construct(StreamInterface $stream)
    {
        if(!$stream->isSeekable()) {
            throw new \RuntimeException('Cannot parse a non-seekable stream');
        }
        $this->stream = $stream;
        if ($this->isValid()) {
            $this->readHeader();
        }
    }

    public function readHeader() {
        $stream = $this->getUncompressedStream();
        $head = $stream->read(12);
        $this->bitSize = (substr($head,7,1) === '-') ? 64 : 32;
        $this->endian = substr($head,8,1) === 'V';
        $this->version = (int) substr($head,9,2);
    }

    public function isCompressed() : bool
    {
        $header = $this->stream->read(7);
        $result = $header !== 'BLENDER';
        $this->stream->rewind();
        return $result;
    }

    public function isValid() : bool
    {
        $result =  $this->getUncompressedStream()->read(7) === 'BLENDER';
        return $result;
    }

    public function getUncompressedStream() : StreamInterface
    {
        if ($this->isCompressed()) {
            return new InflateStream($this->stream);
        } else {
            return $this->stream;
        }
    }

    private function createCompressedTmpfile () : StreamInterface
    {
        $tmpfilename = tempnam(sys_get_temp_dir(),'php');
        $tmpfile = gzopen($tmpfilename,'wb9');
        while (!$this->stream->eof()) gzwrite($tmpfile,$this->stream->read(8192));
        $this->stream->rewind();
        register_shutdown_function(function () use ($tmpfilename) {
            unlink($tmpfilename);
        });
        return new Stream(fopen($tmpfilename,'rb'));
    }


    public function getThumbnailBuffer ()
    {
        function unpackBytes ($endian,$type,$data) {
            if(!$endian) {
                $data = strrev($data);
            }
            switch ($type) {
                case 'int':
                    return hexdec(bin2hex($data));
            }
        }

        $stream = $this->getUncompressedStream();
        $headSize = ($bitSize === 64) ? 24 : 20;

        while (true) {
            $head = $stream->read($headSize);
            $code = substr($head,0,4);
            $length = unpackBytes($this->endian,'int',substr($head,4,8));
            if ($code === 'REND') {
                $stream->read($length);
            } else {
                break;
            }
        }

        if($code !== $test) {
            return null;
        }

        $xy = $stream->read(8);

        $x = $unpackBytes($this->endian,substr($xy,0,4));
        $y = $unpackBytes($this->endian,substr($xy,0,4));

        $length -= 8;

        if ($length !== $x *$y * 4) {
            return null;
        }

        return $stream->read($length);
    }

    public function getCompressedStream() : StreamInterface
    {
        if ($this->isCompressed()) {
            return $this->stream;
        } else {
            //Just creating a tmp file for now. In the future there may be a way to use the deflate stream, which reads the file while deflating.
            return $this->createCompressedTmpfile();
            //return new DeflateStream($this->stream);
        }
    }
}
