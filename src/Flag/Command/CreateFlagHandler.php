<?php declare(strict_types = 1);

namespace BlendExchange\Flag\Command;

use BlendExchange\Flag\Model\Flag;
use BlendExchange\Flag\Data\FlagRepository;

final class CreateFlagHandler
{
    private $flagRepository;

    public function __construct (FlagRepository $flagRepository)
    {
        $this->flagRepository = $flagRepository;
    }

    public function handle(CreateFlag $command) : Flag
    {
        $flag = Flag::create($command->getBlend()->id,$command->getOffense(),$command->getMessage(),$command->getIpAddress());
        $this->flagRepository->add($flag);
        return $flag;
    }
}