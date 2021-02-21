<?php

namespace BlendExchange\Blend\Command;

use BlendExchange\Blend\Model\BlendFile;
use GuzzleHttp\Psr7\StreamWrapper;
use BlendExchange\Filesystem\BulkStorage;
use Monolog\Logger;

class UploadBlendHandler
{
    private $fileSystem;
    private $logger;

    public function __construct(BulkStorage $fileSystem, Logger $logger)
    {
        $this->fileSystem = $fileSystem;
        $this->logger = $logger;
    }

    public function handle(UploadBlend $command) 
    {
        $blend = BlendFile::find($command->getBlendId());
        $stream = $command->getStream();
        $blend->fileSize = $stream->getSize();
        // I switched to using the file path instead of Google's ID
        // so I could support Backblaze as well. A google ID of "new" 
        // means I use the new method of looking up files.
        $blend->fileGoogleId = "new";
        $this->logger->info(sprintf("Uploading stream: %s, file id: %s",$stream->getMetadata()['uri'],$blend->id));
        $resource = StreamWrapper::getResource($stream);
        $this->logger->debug(sprintf("Upload stream type: %s, file id: %s",gettype($resource),$blend->id));
        if(!$this->fileSystem->putStream($blend->getStoragePath(),$resource)) {
            throw new \Exception("Unable to upload blend file.");
        }
        $this->logger->debug(sprintf("Upload stream type: %s, file id: %s",gettype($resource),$blend->id));
        $this->logger->info(sprintf("Uploading complete, file id: %s",$blend->id));
        $blend->save();
        return $blend;
    }
}
