<?php declare(strict_types = 1);

namespace BlendExchange\Authentication\Token;

interface StatelessTokenValidator {
    public function validate (StatelessToken $token, string $permission,string $subjectId): bool;
}