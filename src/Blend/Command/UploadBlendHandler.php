<?php

namespace BlendExchange\Blend\Command;

use BlendExchange\Blend\Model\BlendFile;
use GuzzleHttp\Psr7\StreamWrapper;
use BlendExchange\Filesystem\BulkStorage;

class UploadBlendHandler
{
    private $fileSystem;

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
        if(!$this->fileSystem->putStream("new/" . $blend->id . ".blend",$resource)) {
            throw new \Exception("Unable to upload blend file.");
        }
  
        $blend->fileGoogleId = "new/" . $blend->id . ".blend";
        $blend->save();
        return $blend;
    }
}
