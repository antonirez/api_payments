<?php

namespace App\Service\User;

use App\Dto\User\UserRegistrationDto;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    public function register(UserRegistrationDto $dto): Users
    {
        $user = new Users();
        $user->setEmail($dto->email);

        // hash the plain password and set it
        $hashed = $this->passwordHasher->hashPassword($user, $dto->plainPassword);
        $user->setPassword($hashed);

        // assign default role
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}