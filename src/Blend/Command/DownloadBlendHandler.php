<?php  declare(strict_types = 1);

namespace BlendExchange\Blend\Command;

use BlendExchange\Blend\Model\BlendFile;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\PumpStream;
use BlendExchange\Blend\Data\BlendRepository;
use BlendExchange\Filesystem\BulkStorage;

class DownloadBlendHandler
{
    private $flysystem;
    private $http;
    private $chunkStart = 0;
    public $blend;
    private $blendRepository;

    public function __construct(
        BulkStorage $flysystem,
        BlendRepository $blendRepository
    )
    {
        $this->flysystem = $flysystem;
        $this->chunkStart = 0;
        $this->blendRepository = $blendRepository;
    }

    public function handle(DownloadBlend $command) : StreamInterface
    {
        $blend = $this->blendRepository->findBlendById($command->getBlendId());
        if ($blend === null) {
            throw new \Exception('Blend Not Found');
        }
        return $this->flysystem->readStream($blend->fileGoogleId);
    }
}
