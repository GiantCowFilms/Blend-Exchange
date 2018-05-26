<?php declare(strict_types=1);

namespace BlendExchange\User\Validation;

use BlendExchange\User\Model\User;

final class UserValidator 
{
    public function __construct () {

    }

    public function canAuthenticate(User $user) {
        return $user->email !== null;
    }
}