<?php declare(strict_types=1);

namespace BlendExchange\User\Command;

use BlendExchange\User\Model\User;

final class GrantAdminHandler {
    public function __construct () {

    }

    public function handle(GrantAdmin $command) {
        $user = $command->getUser();
        $user->role = User::ADMIN_ROLE;
        $user->save();
    }
}