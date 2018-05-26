<?php declare(strict_types=1);

namespace BlendExchange\User\Http\Transformer;

use BlendExchange\User\Model\User;
use League\Fractal\TransformerAbstract;

final class UserTransformer extends TransformerAbstract 
{
    public function transform (User $user) 
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'account_type' => $user->account_type,
            'roles' => $user->roles
        ];
    }
}