<?php

namespace App\CustomServices;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CSVImportService extends AbstractController
{
    protected $em;
    protected $userRepository;
    protected $encoder;
    protected $mailer;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {
        // parent::__construct();
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->mailer = $mailer;
    }

    public function getDataFromFile(): array
    {
        $file = 'CSV/users.csv';
        
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
        foreach ($this->getDataFromFile() as $row) {
            if (array_key_exists('email', $row) && !empty($row['email'])) {
                $user = $this->userRepository->findOneBy([
                    'email' => $row['email']
                ]);

                if (!$user) {
                    $user = new User;
                    $hashPassword = $this->encoder->encodePassword($user, $row['password']);
                    $user
                        ->setEmail($row['email'])
                        ->setFirstName($row['firstName'])
                        ->setLastName($row['lastName'])
                        ->setRoles(["ROLE_USER"])
                        ->setPassword($hashPassword)
                        ->setUsername($row['email'])
                        ->setVerified(false)
                        ->setPseudo($row['username'] ? $row['username'] : $row['firstName'] . $row['lastName'] . mt_rand(1, 10));
                    $this->em->persist($user);

                    $email = (new TemplatedEmail())
                        ->from('gircor@gircor.net')
                        ->to($row['email'])
                        ->subject('Inscription sur le site InfoActivisme-Recherche')
                        ->htmlTemplate('mails/subscribe.html.twig')
                        ->context([
                            'username'=>$row['email']
                        ]);
                    $this->mailer->send(($email));
                }
            }
        }

        $this->em->flush();
    }
}
