<?php declare(strict_types = 1);

namespace BlendExchange\Input;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\Stream;

final class Data {
    private $handle;

    public function __construct ($input) {
        $tmpfile = tmpfile();
        $this->copyInputToTemp($input,$tmpfile);
        rewind($tmpfile);
        $this->handle = $tmpfile;
    }

    /**
     * Copy's php://input to a temporary file that can be seeked
     * @param resource handle
     * @return resource
     */
    private function copyInputToTemp ($input, $tmpfile) 
    {
        $size = 0;
        while (!feof($input)) $size += fwrite($tmpfile,fread($input,8192));
        return $size; 
    }

    public function getStream () : StreamInterface
    {
        return new Stream($this->handle);
    }
}