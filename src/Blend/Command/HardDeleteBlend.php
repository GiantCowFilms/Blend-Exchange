<?php declare(strict_types=1);
namespace BlendExchange\Blend\Command;
use BlendExchange\Authorization\User;
use BlendExchange\Blend\Model\BlendFile;

final class HardDeleteBlend {
    
    private $blend;
    private $reason;
    private $user; 
    public function __construct (User $user, BlendFile $blend, string $reason) {
        $this->blend = $blend;
        $this->reason = $reason;
        $this->user = $user;
    }

    public function getUser() : User {
        return $this->user;
    }

    public function getBlend() : BlendFile {
        return $this->blend;
    }

    public function getReason() : string
    {
        return $this->reason;
    }
}