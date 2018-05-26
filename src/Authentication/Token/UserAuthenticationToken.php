<?php declare(strict_types = 1);

namespace BlendExchange\Authentication\Token;

use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

final class UserAuthenticationToken implements StatelessToken {
    private $token;
    private $signer;
    private $key;
    private $validationData;
    private $type;
    
    public function __construct(Token $token, Signer $signer, string $key)
    {
        $this->token = $token;
        $this->signer = $signer;
        $this->key = $key;
        $this->type = 'UserAuthenticationToken';
    }

    public function getUserId() : string
    {
        return $this->token->getClaim('sub');
    }

    public function verify () : bool
    {
        return $this->token->verify($this->signer,$this->key) && $this->token->getClaim('type') === $this->type;
    }

    public function roles () : array
    {
        return $this->token->getClaim('roles');
    }

    public function validate(ValidationData $validationData) {
        return $this->token->validate($validationData);
    }

    public function __toString() : string {
        return $this->token->__toString();
    }
}