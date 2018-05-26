<?php declare(strict_types=1);

namespace BlendExchange\Blend\Command;

final class SoftDeleteBlendHandler {
    public function __construct () {

    }

    public function handle(SoftDeleteBlend $command) {
        $blend = $command->getBlend();
        $blend->delete();
    }
}