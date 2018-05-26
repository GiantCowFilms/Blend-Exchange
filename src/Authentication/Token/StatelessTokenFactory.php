<?php declare(strict_types=1);

namespace BlendExchange\Authentication\Token;

use Symfony\Component\HttpFoundation\Request;

interface StatelessTokenFactory {
    public function createFormToken (Request $request) : StatelessToken;
}