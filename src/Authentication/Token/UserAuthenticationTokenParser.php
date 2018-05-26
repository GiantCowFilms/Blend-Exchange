<?php declare(strict_types = 1);

namespace BlendExchange\Authentication\Token;

use Lcobucci\JWT;

final class UserAuthenticationTokenParser implements StatelessTokenParser {

    private $token;
    private $signer;
    private $key;
    private $tokenParser;

    public function __construct (JWT\Parser $parser,JWT\Signer $signer, string $key) {
        $this->signer = $signer;
        $this->key = $key;
        $this->tokenParser = $parser;
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

    public function parseToken(string $token) : ?UserAuthenticationToken
    {
        $token = $this->formatToken($token);
        try {
            $token = $this->tokenParser->parse($token);
        } catch (\InvalidArgumentException $e) {
            return null;
        }
        return new UserAuthenticationToken($token,$this->signer,$this->key);
    }
}