<?php  declare(strict_types = 1);

namespace BlendExchange\Blend\Command;

use BlendExchange\Blend\Model\BlendFile;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\PumpStream;
use BlendExchange\Blend\Data\BlendRepository;

class DownloadBlendHandler
{
    private $googleDriveService;
    private $http;
    private $chunkStart = 0;
    public $blend;
    private $blendRepository;

    public function __construct(
        \Google_Service_Drive $googleDriveService,
        BlendRepository $blendRepository
    )
    {
        $this->googleDriveService = $googleDriveService;
        $this->chunkStart = 0;
        $this->blendRepository = $blendRepository;
    }

    public function handle(DownloadBlend $command) : StreamInterface
    {
        $blend = $this->blendRepository->findBlendById($command->getBlendId());
        if ($blend === null) {
            throw new \Exception('Blend Not Found');
        }
        $chunkSizeBytes = 1 * 1024 * 1024;
        $chunkEnd = $this->chunkStart + $chunkSizeBytes;
        $fileSize = (int)$this->googleDriveService->files->get($blend->fileGoogleId, ['fields' => 'size'])->size;
        $http = $this->googleDriveService->getClient()->authorize();
        return new PumpStream(function ($length) use ($blend,$http,$fileSize) {
            if ($this->chunkStart >= $fileSize) {
                return false;
            }
            $response = $http->request(
                'GET',
                sprintf('/drive/v3/files/%s', $blend->fileGoogleId),
                [
                    'query' => ['alt' => 'media'],
                    'headers' => [
                        'Range' => sprintf('bytes=%s-%s', $this->chunkStart, min($this->chunkStart + $length, $fileSize))
                    ]
                ]
            );
            $this->chunkStart += $length + 1;
            $chunkData = $response->getBody()->getContents();
            return $chunkData;
        });
    }
}
