<?php declare(strict_types=1);

namespace BlendExchange\Authorization\Role;
use BlendExchange\Authorization\Role;

final class GuestRole extends Role
{
    private $permissions = [
        'ListBlends',
        'ViewBlend',
        'DownloadBlend',
        'CreateBlend',
        'FlagBlend',
        'FavoriteBlend',
        'ViewPublicProfile',
    ];

    public function getPermissions() : array
    {
        return $this->permissions;
    }
}
