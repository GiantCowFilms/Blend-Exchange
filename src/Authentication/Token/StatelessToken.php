<?php declare(strict_types = 1);

namespace BlendExchange\Authentication\Token;

use Lcobucci\JWT;

/**
 * A token that can be passed a StatlessTokenable object to verify
 */
interface StatelessToken {
    public function __toString() : string;
}