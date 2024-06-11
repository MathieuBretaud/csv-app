<?php

namespace App\Service;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveContactsService
{
    public function __construct(
        private ContactRepository      $contactRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }


    public function removeContacts(SymfonyStyle $io): void
    {
        $io->title('Suppression des contacts');

        $contactsRemove = $this->readCsvFile();

        $io->progressStart(count($contactsRemove));

        $contactRemove = [];
        foreach ($contactsRemove as $arrayContact) {
            $io->progressAdvance();

            $contact = $this->contactRepository->findOneBy(['email' => $arrayContact['subscriber_email']]);
            if (!$contact) {
                continue;
            }
            $contactRemove[] = $contact;
            $this->entityManager->remove($contact);
            $this->entityManager->flush();
        }

        $io->progressFinish();

        $io->success(count($contactsRemove) . ' contacts vérifiés et ' . count($contactRemove) . ' supprimés');
    }

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    private function readCsvFile(): Reader
    {
        $csv = Reader::createFromPath('%Kernel.root_dir%/../remove/removes.csv', 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

}