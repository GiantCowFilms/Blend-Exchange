<?php declare(strict_types=1);
namespace BlendExchange\Authentication\Token;

use InvalidArgumentException;
use Lcobucci\JWT;


final class JwtTokenParser implements StatelessTokenParser
{
    private $tokenParser;
    private $signer;
    private $key;


    public function __construct(JWT\Parser $tokenParser,JWT\Signer $signer,string $key)
    {
        $this->tokenParser = $tokenParser;
        $this->signer = $signer;
        $this->key = $key;
    }

    private function formatToken(string $token) : string
    {
        $tokenFormat = '/Bearer\s(.*)/';
        $matches = [];
        if(preg_match($tokenFormat,$token,$matches) === 1) {
            return $matches[1];
        } else {
            return $token;
        }
    }

    public function parseToken (string $token) : ?StatelessToken
    {
        $token = $this->formatToken($token);
        try {
            $parsedToken = $this->tokenParser->parse($token);
        } catch (\InvalidArgumentException $e) { //
            return null;
        }

        return new JwtToken($parsedToken,$this->signer,$this->key);
    }
}
