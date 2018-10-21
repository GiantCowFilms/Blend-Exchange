<?php declare(strict_types=1);

namespace BlendExchange\User\Http\Form;
use BlendExchange\User\Command\SetupUser;
use BlendExchange\User\Command\UpdateUser;
use BlendExchange\User\Model\User;

final class UpdateUserForm
{
    private $errors = [];

    private $email;
    private $username;

    public function __construct (string $email, string $username) 
    {
        $this->email = $email;
        $this->username = $username;
        $this->validate();
    }
    
    private function validate() {
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Please provide a valid email address';
        }
        if (strlen($this->username) < 4) {
            $this->errors['username'] = 'Username must be at least four characters in length';
        }
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    public function getErrors()
    {   
        return $this->errors;
    }

    public function toCommand(User $user) : UpdateUser
    {
        return new UpdateUser($user,$this->email,$this->username);
    }
}