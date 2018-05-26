<?php declare(strict_types=1);

namespace BlendExchange\User\Command;

final class SetupUserHandler
{
    public function __construct () {

    }

    public function handle(SetupUser $command) : void
    {
        $user = $command->getUser();
        $user->email = $command->getEmail();
        if ($command->getUsePassword()) {
            $user->password = password_hash($command->getPassword(),PASSWORD_DEFAULT);
        }

        $user->save();
    }
}