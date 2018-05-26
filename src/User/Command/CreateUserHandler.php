<?php declare(strict_types=1);

namespace BlendExchange\User\Command;

use BlendExchange\User\Model\User;

final class CreateUserHandler
{
    public function __construct () 
    {

    }

    public function handle (CreateUser $command) : User 
    {
        return User::create($command->getStackId(),$command->getUsername(),$command->getStackToken());
    }
}