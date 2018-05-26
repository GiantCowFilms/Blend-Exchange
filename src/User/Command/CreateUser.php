<?php declare(strict_types=1);

namespace BlendExchange\User\Command;

final class CreateUser 
{
    private $username;
    private $stackId;
    private $stackToken;

    public function __construct(string $stackId,string $username, string $stackToken)
    {
        $this->username = $username;
        $this->stackId = $stackId;
        $this->stackToken = $stackToken;
    }

    public function getUsername () 
    {
        return $this->username;
    }

    public function getStackId ()
    {
        return $this->stackId;
    }

    public function getStackToken () {
        return $this->stackToken;
    }
}