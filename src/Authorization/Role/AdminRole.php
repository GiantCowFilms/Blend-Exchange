<?php declare(strict_types=1);

namespace BlendExchange\Authorization\Role;
use BlendExchange\Authorization\Role;

final class AdminRole extends Role
{
    private $permissions = [
        'ModerateBlend',
        'SoftDeleteBlend',
        'HardDeleteBlend',
        'ViewUser',
        'AdminQuery',
        'AdminComment'
    ];

    public function getPermissions() : array
    {
        return $this->permissions;
    }
}
