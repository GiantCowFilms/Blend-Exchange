<?php declare(strict_types = 1);
namespace BlendExchange\Authorization;

final class AuthenticatedUser implements User {
    private $id;
    private $roles = [];

    /**
     * @param Role[] $roles
     */
    public function __construct($id, array $roles)
    {
        $this->id = $id;
        $this->roles = $roles;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function hasPermission($permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}