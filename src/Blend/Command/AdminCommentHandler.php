<?php declare(strict_types=1);

namespace BlendExchange\Blend\Command;

final class AdminCommentHandler {
    public function __construct () {

    }

    public function handle(AdminComment $command) {
        $blend = $command->getBlend();
        $blend->adminComment = $command->getAdminComment();
        $blend->save();
    }
}