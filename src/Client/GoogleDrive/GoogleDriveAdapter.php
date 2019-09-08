<?php declare(strict_types = 1);
namespace BlendExchange\Client\GoogleDrive;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\Config;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\PumpStream;

class GoogleDriveAdapter extends NullAdapter {
    private $googleDriveService;

    public function __construct(\Google_Service_Drive $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    /**
     * @inheritdoc
     */
    public function writeStream($path, $resource, Config $config) {
        $path = substr($path,4);
        /**
         * Upload to Google Drive
         */
        //Set size of chuncks for upload
        $chunkSizeBytes = 1 * 1024 * 1024;

        $drive_file = new \Google_Service_Drive_DriveFile();
        $drive_file->setName($path);
        $drive_file->setDescription('Blend-Exchange User File');
        $drive_file->setMimeType('application/x-blender');
        stream_set_chunk_size($resource,$chunkSizeBytes);
        $dataSize = fstat($resource)['size'];

        $this->googleDriveService->getClient()->setDefer(true);
        $request = $this->googleDriveService->files->create($drive_file, [

        ]);

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
        // Upload the various chunks. $status will be false until the process is
        // complete.
        $status = false;
        rewind($resource);
        while (!$status && !feof($resource)) {
            $chunk = fread($resource,$chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        // The final value of $status will be the data from the API for the object
        // that has been uploaded.
        $result = false;
        if ($status != false) {
            $result = $status;
        }

        // Reset to the client to execute requests immediately in the future.
        $this->googleDriveService->getClient()->setDefer(false);

        if (!$result) {
            throw new \Exception("File upload failed (API Failure)");
            return false;
        }

        $createdFile = $result;
        return [
            'path' => $createdFile->id,
            'type' => 'file'
        ];
    }

    private function getGoogleId(string $path) : ?string {
        // If the file has a blend-extension, it is identified by name
        // Otherwise, it is a legacy file, and the path is the fileGoogleId.
        if(pathinfo($path, PATHINFO_EXTENSION) === "blend") {
            //Find the file in google drive by name. Note that it is directory unaware
            $files = $this->googleDriveService->files->listFiles([
                'q' => "name contains '" . basename($path) . "'",
                'fields' => 'files(id,size,name)'
            ]);
            $fileGoogleId = isset($files[0]) ? $files[0]->id : null;
        } else {
            $fileGoogleId = $path;
        }
        return $fileGoogleId;
    }


    /**
     * @inheritdoc
     */
    public function readStream($path)
    {
        $chunkStart = 0;
        $fileGoogleId = $this->getGoogleId($path);
        $chunkSizeBytes = 1 * 1024 * 1024;
        $chunkEnd = $chunkStart + $chunkSizeBytes;
        $fileSize = (int)$this->googleDriveService->files->get($fileGoogleId, ['fields' => 'size'])->size;
        $http = $this->googleDriveService->getClient()->authorize();
        $stream = new PumpStream(function ($length) use ($fileGoogleId,$http,$fileSize,&$chunkStart) {
            if ($chunkStart >= $fileSize) {
                return false;
            }
            $response = $http->request(
                'GET',
                sprintf('/drive/v3/files/%s', $fileGoogleId),
                [
                    'query' => ['alt' => 'media'],
                    'headers' => [
                        'Range' => sprintf('bytes=%s-%s', $chunkStart, min($chunkStart + $length, $fileSize))
                    ]
                ]
            );
            $chunkStart += $length + 1;
            $chunkData = $response->getBody()->getContents();
            return $chunkData;
        });
        return [
            'path' => $path,
            'stream' => $stream,
            'type' => 'file'
        ];
    }

    /**
     * @inheritdoc
     */
    public function has($path) {
        return $this->getGoogleId($path) !== null;
    }
}