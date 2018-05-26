<?php declare(strict_types=1);

namespace BlendExchange\User\Command;

use BlendExchange\Authentication\Token\UserAuthenticationTokenGenerator;
use BlendExchange\User\Model\Authentication;

final class AuthenticateUserHandler {
    public function __construct (UserAuthenticationTokenGenerator $userTokenGenerator) {
        $this->userTokenGenerator = $userTokenGenerator;
    }

    public function handle(AuthenticateUser $command)
    {
        $user = $command->getUser();
        if($user->login($command->getPassword())) {
            $token = $this->userTokenGenerator->generate($user);
            return new Authentication($user,$token);
        }
        return false;
    }
}