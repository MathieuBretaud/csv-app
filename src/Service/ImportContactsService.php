<?php

namespace App\Service;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportContactsService
{
    public function __construct(
        private ContactRepository      $contactRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function importContacts(SymfonyStyle $io): void
    {
        $io->title('Importation des contacts');

        $contacts = $this->readCsvFile();

        $io->progressStart(count($contacts));

        foreach ($contacts as $arrayContact) {
            $io->progressAdvance();
            $contact = $this->createOrUpdateContact($arrayContact);
            $this->entityManager->persist($contact);
        }

        $this->entityManager->flush();

        $io->progressFinish();

        $io->success('Importation terminée!');
    }

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    private function readCsvFile(): Reader
    {
        $csv = Reader::createFromPath('%Kernel.root_dir%/../import/contacts.csv', 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    public function createOrUpdateContact(array $arrayContact): Contact
    {
        $contact = $this->contactRepository->findOneBy(['email' => $arrayContact['EMAIL']]);

        if (!$contact) {
            $contact = new Contact();
        }
        $contact->setEmail($arrayContact['EMAIL'])
            ->setNom($arrayContact['NOM'] ?? '')
            ->setPrenom($arrayContact['PRENOM'] ?? '')
            ->setTelephone($arrayContact['TELEPHONE'] ?? $arrayContact['NUMERO_TEL'] ?? '')
            ->setSms($arrayContact['SMS'] ?? '')
            ->setAdresse($arrayContact['ADRESSE'] ?? '')
            ->setEnseigneCommercial($arrayContact['ENSEIGNE_COMMERCIALE'] ?? '')
            ->setVille($arrayContact['VILLE'] ?? '')
            ->setCodePostal($arrayContact['CODE_POSTAL'] ?? '')
        ;
        return $contact;
    }
}