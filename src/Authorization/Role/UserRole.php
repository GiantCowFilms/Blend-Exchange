<?php declare(strict_types=1);

namespace BlendExchange\Authorization\Role;
use BlendExchange\Authorization\Role;

final class UserRole extends Role
{
    private $permissions = [
        'CreateBlend',
        'DownloadBlend',
        'ListBlends',
        'ViewProfile',
        'ViewBlendList',
        'FlagBlend',
        'CurrentUser'
    ];

    public function getPermissions() : array
    {
        return $this->permissions;
    }
}
