<?php

namespace App\Command;

use App\Service\UpdateContactsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:update-contacts')]
class UpdateContactsCommand extends Command
{
    public function __construct(
        private readonly UpdateContactsService $updateContactsService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->updateContactsService->updateContacts($io);

        return Command::SUCCESS;
    }
}