<?php declare(strict_types=1);

namespace BlendExchange\Site\Console;

use BlendExchange\Filesystem\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UpCommand extends Command {

    public function __construct (
        Storage $storage
    ) {
        $this->storage = $storage;
        parent::__construct();
    }
    public function configure()
    {
        $this->setName('site:up');
        $this->setDescription('Takes the site out of maintenance mode.');
        $this->setHelp('Makes the site public');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $this->storage->delete('/site/downfile');
        $output->writeln('Site is up!');
    }
}