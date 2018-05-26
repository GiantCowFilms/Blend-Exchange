<?php declare(strict_types=1);

namespace BlendExchange\Blend\Command;

use BlendExchange\Access\Model\Access;

final class FavoriteBlendHandler 
{
    public function __construct () {

    }

    public function handle (FavoriteBlend $command)
    {
        if(Access::where('type','favorite')->where('fileId',$command->getBlendId())->where('ip',$command->getIpAddress())->count() === 0) {
            $access = new Access();
            $access->ip = $command->getIpAddress();
            $access->type = 'favorite';
            $access->fileId = $command->getBlendId();
            $access->save();
        }
    }
}