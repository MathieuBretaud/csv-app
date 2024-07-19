<?php

namespace App\Service;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateContactsService
{
    public function __construct(
        private ContactRepository      $contactRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function updateContacts(SymfonyStyle $io): void
    {
        $io->title('Mise à jour des contacts');

        $contacts = $this->readCsvFile();

        $io->progressStart(count($contacts));

        foreach ($contacts as $arrayContact) {
            $io->progressAdvance();
//            $contact = $this->updateContact($arrayContact);

            $contact = $this->contactRepository->findOneBy(['email' => $arrayContact['EMAIL']]);

            if (!$contact) {
                continue;
            }
            $contact->setEmail($arrayContact['EMAIL'])
                ->setNom($arrayContact['NOM'] ?? '')
                ->setPrenom($arrayContact['PRENOM'] ?? '')
                ->setTelephone($arrayContact['TELEPHONE'] ?? $arrayContact['NUMERO_TEL'] ?? '')
                ->setSms($arrayContact['SMS'] ?? '')
                ->setAdresse($arrayContact['ADRESSE'] ?? '')
                ->setEnseigneCommercial($arrayContact['ENSEIGNE_COMMERCIALE'] ?? '')
                ->setVille($arrayContact['VILLE'] ?? '')
                ->setCodePostal($arrayContact['CODE_POSTAL'] ?? '');

            $this->entityManager->persist($contact);
        }

        $this->entityManager->flush();

        $io->progressFinish();

        $io->success('Mise à jour terminée!');
    }

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    private function readCsvFile(): Reader
    {
        $csv = Reader::createFromPath('%Kernel.root_dir%/../update/contacts.csv', 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

//    public function updateContact(array $arrayContact): Contact|void
//    {
//        $contact = $this->contactRepository->findOneBy(['email' => $arrayContact['EMAIL']]);
//
//        if (!$contact) {
//            continue;
//        }
//        $contact->setEmail($arrayContact['EMAIL'])
//            ->setNom($arrayContact['NOM'] ?? '')
//            ->setPrenom($arrayContact['PRENOM'] ?? '')
//            ->setTelephone($arrayContact['TELEPHONE'] ?? $arrayContact['NUMERO_TEL'] ?? '')
//            ->setSms($arrayContact['SMS'] ?? '')
//            ->setAdresse($arrayContact['ADRESSE'] ?? '')
//            ->setEnseigneCommercial($arrayContact['ENSEIGNE_COMMERCIALE'] ?? '')
//            ->setVille($arrayContact['VILLE'] ?? '')
//            ->setCodePostal($arrayContact['CODE_POSTAL'] ?? '');
//        return $contact;
//    }
}