<?php

namespace App\Command;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateDefaultUserCommand extends Command
{
    protected static $defaultName = 'app:create-default-user';
    protected static $defaultDescription = 'Crea un usuario JWT por defecto si no existe';

    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'Email del usuario por defecto', 'admin@example.com')
            ->addArgument('password', InputArgument::OPTIONAL, 'Contraseña del usuario por defecto', 'adminpass');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $plain = $input->getArgument('password');

        $repo = $this->em->getRepository(Users::class);
        $existing = $repo->findOneBy(['email' => $email]);

        if ($existing) {
            $output->writeln("<comment>El usuario {$email} ya existe..</comment>");
            return Command::SUCCESS;
        }

        $user = new Users();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $hashed = $this->passwordHasher->hashPassword($user, $plain);
        $user->setPassword($hashed);

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln("<info>Usuario por defecto {$email} creado correctamente.</info>");
        return Command::SUCCESS;
    }
}
