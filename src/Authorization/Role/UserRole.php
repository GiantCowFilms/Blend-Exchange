<?php declare(strict_types=1);

namespace BlendExchange\Authorization\Role;
use BlendExchange\Authorization\Role;

final class UserRole extends Role
{
    private $permissions = [
        'ListBlends',
        'ViewBlend',
        'DownloadBlend',
        'CreateBlend',
        'FlagBlend',
        'FavoriteBlend',
        'AttachUploadedBlend',
        'AttachExistingBlend',
        'ViewPublicProfile',
        'CurrentUser',
        'ViewPrivateSettings',
        'EditPrivateSettings'
    ];

    public function getPermissions() : array
    {
        return $this->permissions;
    }
}
