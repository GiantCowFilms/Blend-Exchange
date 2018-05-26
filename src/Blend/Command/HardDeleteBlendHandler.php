<?php declare(strict_types=1);

namespace BlendExchange\Blend\Command;

use BlendExchange\Blend\Command\HardDeleteBlend;
use BlendExchange\Blend\Data\BlendRepository;

final class HardDeleteBlendHandler {
    public function __construct (\Google_Service_Drive $googleDriveService,BlendRepository $blendRepository) {
        $this->googleDriveService = $googleDriveService;
        $this->blendRepository = $blendRepository;
    }

    public function handle(HardDeleteBlend $command) {
        $blend = $command->getBlend();
        $this->googleDriveService->files->delete($blend->fileGoogleId);
        $this->blendRepository->remove($blend);
    }
}