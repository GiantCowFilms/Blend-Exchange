<?php declare(strict_types=1);

namespace BlendExchange\Blend\Command;
use BlendExchange\Blend\Model\BlendFile;


final class CreateBlendHandler {
    public function __construct () {

    }

    public function handle (CreateBlend $command) {
        $blend = new BlendFile();
        $blend->uploaderIp = $command->getUploaderIp();
        $blend->questionLink = $command->getQuestionLink();
        $blend->fileName = $command->getFileName();
        if ($command->getUser()->hasPermission('AttachUploadedBlend')) {
            $blend->owner = $command->getOwner();   
        } else {
            $blend->owner = null;
        }
        $blend->save();
        return $blend;
    }
}