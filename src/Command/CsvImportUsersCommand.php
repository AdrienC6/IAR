<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CsvImportUsersCommand extends Command
{
    private SymfonyStyle $io;
    protected $em;
    private string $dataDirectory;
    protected $userRepository;
    protected $encoder;

    public function __construct(EntityManagerInterface $em, string $dataDirectory, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct();
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->dataDirectory = $dataDirectory;
        $this->encoder = $encoder;
    }

    protected static $defaultName = 'csv:import-users';
    protected static $defaultDescription = 'Import Users from CSV file';

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription);
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createUsers();

        return Command::SUCCESS;
    }

    private function getDataFromFile(): array
    {
        $file = $this->dataDirectory . 'users.csv';

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        $normalizers = [new ObjectNormalizer()];

        $encoders = [
            new CsvEncoder()
        ];

        $serializer = new Serializer($normalizers, $encoders);

        /** @var string $filestring */
        $fileString = file_get_contents($file);

        $data = $serializer->decode($fileString, $fileExtension);

        if (array_key_exists('results', $data)) {
            return $data['results'];
        }
        return $data;
    }

    public function createUsers(): void
    {
        $this->io->section('Creation of users from CSV file');

        $usersCreated = 0;

        foreach ($this->getDataFromFile() as $row) {
            if (array_key_exists('email', $row) && !empty($row['email'])) {
                $user = $this->userRepository->findOneBy([
                    'email' => $row['email']
                ]);

                // $username = $this->userRepository->findOneBy([
                //     'username' => $row['username']
                // ]);

                if (!$user) {
                    $user = new User;
                    $hashPassword = $this->encoder->encodePassword($user, $row['password']);
                    $user
                        ->setEmail($row['email'])
                        ->setFirstName($row['firstName'])
                        ->setLastName($row['lastName'])
                        ->setRoles(["ROLE_USER"])
                        ->setPassword($hashPassword)
                        ->setUsername($row['firstName'] . $row['lastName'] . mt_rand(1, 10));

                    $this->em->persist($user);
                    $usersCreated++;
                }
            }
        }

        $this->em->flush();

        if ($usersCreated > 1) {
            $string = "$usersCreated users ont été ajoutés";
        } elseif ($usersCreated = 1) {
            $string = '1 user a été ajouté';
        } else {
            $string = 'Aucun utilisateur n\'a été ajouté';
        }

        $this->io->success($string);
    }
}
