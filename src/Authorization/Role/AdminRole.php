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
        'AdminComment',
        'AcceptFlag',
        'DeclineFlag'
    ];

    public function getPermissions() : array
    {
        return $this->permissions;
    }
}
