<?php declare(strict_types = 1);

namespace BlendExchange\Flag\Command;

final class DeclineFlag
{
    private $flagId;

    public function __construct(string $flagId)
    {
        $this->flagId = $flagId;
    }

    public function getFlagId() : string
    {
        return $this->flagId;
    }
}
