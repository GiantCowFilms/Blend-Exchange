<?php declare(strict_types=1);

namespace BlendExchange\Blend\Command;

use BlendExchange\Blend\Model\BlendFile;

final class AdminComment {
    private $adminComment;
    private $blend;

    public function __construct (BlendFile $blend, string $adminComment) {
        $this->blend = $blend;
        $this->adminComment = $adminComment;
    }

    public function getBlend() : BlendFile {
        return $this->blend;
    }

    public function getAdminComment() : string {
        return $this->adminComment;
    }
}