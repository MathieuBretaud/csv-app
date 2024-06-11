<?php

namespace App\Command;

use App\Service\ImportContactsService;
use App\Service\RemoveContactsService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:remove-contacts')]
class RemoveContactsCommand extends Command
{
    public function __construct(
        private RemoveContactsService $removeContactsService,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->removeContactsService->removeContacts($io);

        return Command::SUCCESS;
    }
}