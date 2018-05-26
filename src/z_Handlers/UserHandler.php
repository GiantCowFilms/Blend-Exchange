<?php  declare(strict_types = 1);

namespace BlendExchange\Handlers;
use BlendExchange\Models\User;
use BlendExchange\Blend\Model\BlendFile;

class UserHandler {
    function __construct(AuthHandler $authHandler,\Valitron\Validator $v) {
        $this->authHandler = $authHandler;
        $this->v = $v;
    }

    public function validateAccountDetails ($data) {
        //Validate Account Details
        $v = $v->withData($data);
        $rules = [
            'email' => ['required','email'],
            'password' => [['lengthBetween',8,1024]]
        ];
        $requiresPassword = $user->admin > 0  || $data['use_password'] === 'true';
        if ($requiresPassword) {
            $rules['password'][] = 'required';
        }
        $v->mapFieldsRules($rules);
        if(!$v->validate()) {
            return $v->errors();
        } else {
            return [];
        }
    }

    

    public function setupUser($user,$data) {
        if ($user->password !== null) {
            throw new \Exception('Account has already been configured');
        }
        if ($user->email === null) {
            $user->email = $data['email'];
        }
        if ($user->admin > 0 || $data['use_password'] === 'true') {
            $user->password = password_hash($data['password'],PASSWORD_DEFAULT);
        }
        $user->save();
    }
}