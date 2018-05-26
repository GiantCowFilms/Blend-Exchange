<?php declare(strict_types=1);

namespace BlendExchange\Authentication\Token;

use BlendExchange\Api\Request\RequestDecoder;
use Symfony\Component\HttpFoundation\Request;

final class JwtTokenFactory implements StatelessTokenFactory {
    private $tokenParser;
    private $requestDecoder;

    public function __construct (JwtTokenParser $tokenParser,RequestDecoder $requestDecoder) 
    {
        $this->tokenParser = $tokenParser;
        $this->requestDecoder = $requestDecoder;
    }

    public function createFormToken(Request $request) : StatelessToken
    {
        return $this->tokenParser->parseToken($this->requestDecoder->decode($request)->meta->token);
    }

    public function createFromResourceToken(Request $request) : StatelessToken
    {
        return $this->tokenParser->parseToken($request->headers->get('X-Resource-Token'));
    }
}