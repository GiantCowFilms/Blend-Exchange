<?php declare(strict_types=1);

namespace BlendExchange\Flag\Data;

use BlendExchange\Flag\Model\Flag;

final class FlagRepository 
{
    public function __construct () {

    }

    public function getFlagById(string $id): Flag
    {
        return Flag::find($id);
    }

    public function add(Flag $flag) : void
    {
        $flag->save();
    }
}