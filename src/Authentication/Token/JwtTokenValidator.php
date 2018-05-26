<?php declare(strict_types = 1);
namespace BlendExchange\Authentication\Token;

use Lcobucci\JWT;

final class JwtTokenValidator implements StatelessTokenValidator
{
    public function __construct(JWT\ValidationData $validationData)
    {
        $this->validationData = $validationData;
    }

    public function validate(StatelessToken $token, string $permission, string $subjectId) : bool
    {
        return $token->verify() && $token->validate($this->validationData) && $token->getType() === $permission && $token->getSubject() === $subjectId;
    }
}
