<?php declare(strict_types=1);

namespace BlendExchange\Handlers;

use BlendExchange\Models\User;
use BlendExchange\Commands\CreateUserCommand;

final class CreateUserHandler
{
    public function __construct () {

    }

    public function handle (CreateUserCommand $command) : User
    {
        return User::create($command->getStackId(),$command->getUsername());
    }
}