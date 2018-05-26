<?php declare(strict_types=1);

namespace BlendExchange\Authentication\Token;
use BlendExchange\User\Model\User;
use Lcobucci\JWT\Signer;

final class UserAuthenticationTokenGenerator
{
    private $generator;
    public function __construct (JwtTokenGenerator $generator,Signer $signer,string $key) {
        $this->generator = $generator;
        $this->signer = $signer;
        $this->key = $key;
    }

    public function generate(User $user,$roles = [])
    {
        $builder = $this->generator->builder();
        $token = $builder->set('type','UserAuthenticationToken')
            ->set('sub',$user->id)
            ->set('roles',$roles)
            ->setExpiration(time() + 86400 * 30)
            ->sign($this->signer,$this->key)
            ->getToken();
        return new UserAuthenticationToken($token,$this->signer,$this->key);
    }
}
