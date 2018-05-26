<?php declare(strict_types = 1);

namespace BlendExchange\Api;

interface Response {
    public function send() : void;
}