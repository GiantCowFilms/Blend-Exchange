<?php declare(strict_types=1);

namespace BlendExchange\Flag\Http\Transformer;

use BlendExchange\Flag\Model\Flag;
use League\Fractal\TransformerAbstract;

final class AdminFlagTransformer extends TransformerAbstract
{
    public function transform(Flag $flag)
    {
        return [
            'id' => $flag->id,
            'offense' => $flag->offense,
            'message' => $flag->message
        ];
    }
}