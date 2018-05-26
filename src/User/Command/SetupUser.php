<?php declare(strict_types=1);

namespace BlendExchange\User\Command;

use BlendExchange\User\Model\User;

final class SetupUser
{
    private $user;
    private $email;
    private $password;
    private $usePassword;

    public function __construct (User $user, string $email,string $password, bool $usePassword) 
    {
        $this->user = $user;
        $this->email = $email;
        $this->password = $password;
        $this->usePassword = $usePassword;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUsePassword()
    {
        return $this->usePassword;
    }
}   