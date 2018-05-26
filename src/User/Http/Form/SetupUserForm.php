<?php declare(strict_types=1);

namespace BlendExchange\User\Http\Form;
use BlendExchange\User\Command\SetupUser;
use BlendExchange\User\Model\User;

final class SetupUserForm
{
    private $errors = [];

    private $email;
    private $password;
    private $usePassword;
    private $termsAndPrivacy;

    public function __construct (string $email, string $password, bool $usePassword, bool $termsAndPrivacy) 
    {
        $this->email = $email;
        $this->password = $password;
        $this->usePassword = $usePassword;
        $this->termsAndPrivacy = $termsAndPrivacy;
        $this->validate();
    }
    
    private function validate() {
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Please provide a valid email address';
        }
        if ($this->usePassword) {
            if (strlen($this->password) < 8)
            {
                $this->errors['password'] = 'Password must be at least 8 characters.';
            }
        }
        if(!$this->termsAndPrivacy) {
            $this->errors['termsAndPrivacy'] = 'Please accept the terms of service and read the privacy policy';
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

    public function toCommand(User $user) : SetupUser
    {
        return new SetupUser($user,$this->email,$this->password,$this->usePassword);
    }
}