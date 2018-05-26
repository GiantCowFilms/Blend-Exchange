<?php declare(strict_types=1);

namespace BlendExchange\User\Http\Transformer;

use BlendExchange\User\Model\User;
use League\Fractal\TransformerAbstract;

final class ProfileTransformer extends TransformerAbstract
{
    public function transform (User $user) {
        return [
            'id' => $user->id,
            'username' => $user->username
        ];
    } 
}