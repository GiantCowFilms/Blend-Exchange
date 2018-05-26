<?php declare(strict_types=1);

namespace BlendExchange\User\Http\Transformer;

use League\Fractal\TransformerAbstract;
use BlendExchange\User\Model\User;

final class PendingAccountTransformer extends TransformerAbstract
{
    public function transform(User $user) 
    {
        return [
            'account_type' => $user->account_type,
            'id' => $user->id
        ];
    }
}