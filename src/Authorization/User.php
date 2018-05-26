<?php declare(strict_types=1);
namespace BlendExchange\Authorization;

interface User
{
    public function hasPermission(string $permission): bool;
    public function getId(): ?string;
}