<?php declare(strict_types = 1);

namespace BlendExchange\Authorization;

abstract class Role {
    public function hasPermission (string $testPermission) : bool
    {
        foreach ($this->getPermissions() as $permission) {
            if($testPermission === $permission) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get list of permissions assigned to this role
     *
     * @return string[] Permissions
     */
    abstract public function getPermissions () : array;
}