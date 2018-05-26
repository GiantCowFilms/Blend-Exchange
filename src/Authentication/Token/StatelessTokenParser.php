<?php declare(strict_types = 1);

namespace BlendExchange\Authentication\Token;

interface StatelessTokenParser
{
    public function parseToken(string $token);
}
