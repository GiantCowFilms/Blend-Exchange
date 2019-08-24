<?php declare(strict_types=1);

namespace BlendExchange\Flag\Command;

use BlendExchange\Flag\Model\Flag;
use BlendExchange\Flag\Data\FlagRepository;

final class DeclineFlagHandler 
{
    private $flagRepository;

    public function __construct (FlagRepository $flagRepository) {
        $this->flagRepository = $flagRepository;
    }

    public function handle(DeclineFlag $command) : void
    {
        $flag = $this->flagRepository->getFlagById($command->getFlagId());
        $flag->accepted = 2;
        $flag->save();
    }
}