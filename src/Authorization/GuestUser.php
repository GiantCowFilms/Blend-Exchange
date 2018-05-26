<?php declare(strict_types = 1);

namespace BlendExchange\Authorization;

final class GuestUser implements User {
    private $id;
    private $roles = [];

    public function __construct () {
        $this->roles = [new Role\GuestRole()];
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

    public function getId(): ?string
    {
        return null;
    }

}