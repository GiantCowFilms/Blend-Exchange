<?php declare(strict_types=1);

namespace BlendExchange\User\Model;
use BlendExchange\Authentication\Token\StatelessToken;

final class Authentication {
    public $user;
    public $token;

    public function __construct (User $user,StatelessToken $token) {
        $this->token = $token;
        $this->user = $user;
    }
}