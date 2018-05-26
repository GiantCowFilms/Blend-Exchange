<?php declare(strict_types=1);
namespace BlendExchange\Authentication\Token;

use Lcobucci\JWT;


interface StatelessTokenGenerator
{
    public function generate (string $subjectId,string $permission) : StatelessToken;
}
