<?php

namespace App\Service\User;

use App\Dto\User\UserRegistrationDto;
use App\Entity\Users;
use App\Validator\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function register(EntityManagerInterface $em, UserRegistrationDto $dto): Users
    {
        $user = $em->getRepository(Users::class)->findOneBy(['email' => $dto->email]);
        if (!empty($user)) {
            throw new ValidationException(serialize(['message' => 'User exists', 'code' => JsonResponse::HTTP_BAD_REQUEST]));
        }

        $user = new Users();
        $user->setEmail($dto->email);

        // hash the plain password and set it
        $hashed = $this->passwordHasher->hashPassword($user, $dto->plainPassword);
        $user->setPassword($hashed);

        // assign default role
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        return $user;
    }
}