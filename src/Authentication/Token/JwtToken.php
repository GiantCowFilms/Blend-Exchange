<?php declare(strict_types = 1);
namespace BlendExchange\Authentication\Token;

use Lcobucci\JWT;

class JwtToken implements StatelessToken
{
    private $token;
    private $signer;
    private $key;

    public function __construct(JWT\Token $token, JWT\Signer $signer, string $key)
    {
        $this->token = $token;
        $this->signer = $signer;
        $this->key = $key;
    }

    public function getSubject()
    {
        return $this->token->getClaim('sub');
    }

    

    public function getType()
    {
        return $this->token->getClaim('type');
    }

    public function verify()
    {
        return $this->token->verify($this->signer,$this->key);
    }

    public function validate(JWT\ValidationData $validationData) {
        return $this->token->validate($validationData);
    }

    public function __toString() : string
    {
        return (string)$this->token;
    }
}
