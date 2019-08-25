<?php

namespace BlendExchange\Blend\Command;

use BlendExchange\Blend\Model\BlendFile;
use GuzzleHttp\Psr7\StreamWrapper;
use BlendExchange\Filesystem\BulkStorage;

class UploadBlendHandler
{
    private $fileSystem;
    private $handle;
    private $chunkStart = 0;
    private $errors = [];

    public function __construct(BulkStorage $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function handle(UploadBlend $command) 
    {
        $blend = BlendFile::find($command->getBlendId());
        $stream = $command->getStream();
        $blend->fileSize = $stream->getSize();
        $resource = StreamWrapper::getResource($stream);
        $this->fileSystem->putStream($blend->id,$resource);
  
        $blend->fileGoogleId = "new/" + $blend->id;
        $blend->save();
        return $blend;
    }
}
