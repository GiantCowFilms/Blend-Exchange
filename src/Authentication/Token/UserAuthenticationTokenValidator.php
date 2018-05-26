<?php declare(strict_types=1);

namespace BlendExchange\Authentication\Token;

use Lcobucci\JWT\ValidationData;

final class UserAuthenticationTokenValidator
{
    public function __construct(ValidationData $validationData)
    {
        $this->validationData = $validationData;
    }

    public function validate (UserAuthenticationToken $token) : bool
    {
        return $token !== null && $token->verify() && $token->validate($this->validationData);
    }
}