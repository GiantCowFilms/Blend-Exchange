<?php declare(strict_types=1);

namespace BlendExchange\User\Command;

use BlendExchange\User\Model\User;

final class UpdateUser {
    public function __construct (User $user,string $email,string $username) {
        $this->user = $user;
        $this->email = $email;
        $this->username = $username;
    }

    public function getUser() : User {
        return $this->user;
    }

    public function getEmail() : string {
        return $this->email;
    }

    public function getUsername() : string {
        return $this->username;
    }
}