<?php declare(strict_types=1);

namespace BlendExchange\Api\Request;

use Symfony\Component\HttpFoundation\Request;

final class RequestDecoder
{
    public function decode(Request $request) : object 
    {
        return json_decode($request->getContent());
    }
}