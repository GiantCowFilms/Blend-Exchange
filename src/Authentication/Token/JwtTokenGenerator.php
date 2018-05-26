<?php declare(strict_types=1);
namespace BlendExchange\Authentication\Token;

use Lcobucci\JWT;


final class JwtTokenGenerator implements StatelessTokenGenerator
{
    public function __construct(JWT\Builder $tokenBuilder,JWT\Signer $signer,string $key)
    {
        $this->tokenBuilder = $tokenBuilder;
        $this->signer = $signer;
        $this->key = $key;
    }

    public function builder () {
        return $this->tokenBuilder->setIssuer('Blend-Exchange')
        ->setAudience('Blend-Exchange')
        ->setIssuedAt(time());
    }

    public function generate (string $subjectId,string $permission, int $expires = 3600) : StatelessToken
    {
        $token = $this->builder()
        ->set('type',$permission)
        ->set('sub',$subjectId)
        ->setExpiration(time() + $expires)
        ->sign($this->signer,$this->key)
        ->getToken();
        
        return new JwtToken($token,$this->signer,$this->key);
    }
}
