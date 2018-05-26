<?php declare(strict_types=1);

namespace BlendExchange\User\Command;

use BlendExchange\User\Model\User;

final class AuthenticateUser {
    public function __construct (User $user,string $password) {
        $this->user = $user;
        $this->password = $password;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPassword () {
        return $this->password;
    }
}