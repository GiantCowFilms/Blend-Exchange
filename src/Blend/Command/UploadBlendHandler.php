<?php

namespace BlendExchange\Blend\Command;

use BlendExchange\Blend\Model\BlendFile;

class UploadBlendHandler
{
    private $googleDriveService;
    private $handle;
    private $chunkStart = 0;
    private $errors = [];

    public function __construct(\Google_Service_Drive $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    public function handle(UploadBlend $command) 
    {
        $blend = BlendFile::find($command->getBlendId());

        /**
         * Upload to Google Drive
         */
        $drive_file = new \Google_Service_Drive_DriveFile();
        $drive_file->setName($blend->id. '.blend');
        $drive_file->setDescription('Blend-Exchange User File');
        $drive_file->setMimeType('application/x-blender');
        $stream = $command->getStream();
        $dataSize = $stream->getSize();

        $this->googleDriveService->getClient()->setDefer(true);
        $request = $this->googleDriveService->files->create($drive_file, [

        ]);

        //Set size of chuncks for upload
        $chunkSizeBytes = 1 * 1024 * 1024;

        // Create a media file upload to represent our upload process.
        $media = new \Google_Http_MediaFileUpload(
          $this->googleDriveService->getClient(),
          $request,
          'application/octet-stream',
          null,
          true,
          $chunkSizeBytes
        );
        $media->setFileSize($dataSize);
        $blend->fileSize = $dataSize;
        // Upload the various chunks. $status will be false until the process is
        // complete.
        $status = false;
        $stream->rewind();
        while (!$status && !$stream->eof()) {
            $chunk = $stream->read($chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        // The final value of $status will be the data from the API for the object
        // that has been uploaded.
        $result = false;
        if ($status != false) {
            $result = $status;
        }

        $stream->close();
        // Reset to the client to execute requests immediately in the future.
        $this->googleDriveService->getClient()->setDefer(false);

        if (!$result) {
            throw new \Exception("File upload failed (API Failure)");
            return;
        }

        $createdFile = $result;
        $blend->fileGoogleId = $createdFile->id;
        $blend->save();
        return $blend;
    }
}
