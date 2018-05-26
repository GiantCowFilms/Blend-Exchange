<?php declare(strict_types=1);
namespace BlendExchange\User\Command;

use BlendExchange\User\Model\User;

final class GrantAdmin {
    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getUser() : User {
        return $this->user;
    }
}