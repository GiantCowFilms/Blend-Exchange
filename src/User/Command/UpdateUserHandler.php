<?php declare(strict_types=1);

namespace BlendExchange\User\Command;

final class UpdateUserHandler
{
    public function __construct () {

    }

    public function handle(UpdateUser $command) : void
    {
        $user = $command->getUser();
        $user->email = $command->getEmail();
        $user->username = $command->getUsername();
        $user->save();
    }
}