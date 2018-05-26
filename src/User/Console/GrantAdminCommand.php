<?php declare(strict_types=1);

namespace BlendExchange\User\Console;

use BlendExchange\User\Command\GrantAdmin;
use BlendExchange\User\Command\GrantAdminHandler;
use BlendExchange\User\Data\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GrantAdminCommand extends Command {

    private $userRepository;
    private $grantAdminHandler;

    public function __construct (
        UserRepository $userRepository,
        GrantAdminHandler $grantAdminHandler
    ) {
        $this->userRepository = $userRepository;
        $this->grantAdminHandler = $grantAdminHandler;
        parent::__construct();
    }

    public function configure()
    {
        $this->addArgument('user-id', InputArgument::REQUIRED);
        $this->setName('user:grant-admin');
        $this->setDescription('Elevate a user to admin.');
        $this->setHelp('Elevates a user to admin. Use the user ID not the stack ID.');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $id = $input->getArgument('user-id');
        $user = $this->userRepository->findUserById($id);
        if ($user === null) {
            $output->writeln('User ' . $id .' not found.');
            return 0;
        }
        if($user->account_type !== 'password') {
            $output->writeln('Users must set password receiving admin permissions');
            return 0;
        }
        $command = new GrantAdmin($user);
        $this->grantAdminHandler->handle($command);
        $output->writeln('Granted user ' . $user->id .' (' . $user->username . ') admin powers.');
    }
}