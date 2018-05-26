<?php declare(strict_types=1);

namespace BlendExchange\Site\Console;

use BlendExchange\Filesystem\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class DownCommand extends Command {

    public function __construct (
        Storage $storage
    ) {
        $this->storage = $storage;
        parent::__construct();
    }

    public function configure()
    {
        $this->addArgument('message', InputArgument::REQUIRED);
        $this->addOption('allowed','a',InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,'IP addresses permitted access while site is down.',[]);
        $this->setName('site:down');
        $this->setDescription('Puts site into maintenance mode.');
        $this->setHelp('Takes the site down publicly');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $this->storage->put('/site/downfile', json_encode([
            'message' => $input->getArgument('message'),
            'allowed' => array_merge($input->getOption('allowed'),[
                '127.0.0.1'
            ])
        ]));
        $output->writeln('Site is down!');
    }
}