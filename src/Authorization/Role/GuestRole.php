<?php declare(strict_types=1);

namespace BlendExchange\Authorization\Role;
use BlendExchange\Authorization\Role;

final class GuestRole extends Role
{
    private $permissions = [
        'CreateBlend',
        'DownloadBlend',
        'ListBlends',
        'ViewProfile',
        'ViewBlendList',
        'FlagBlend',
    ];

    public function getPermissions() : array
    {
        return $this->permissions;
    }
}
