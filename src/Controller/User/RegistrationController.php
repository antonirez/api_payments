<?php

namespace App\Controller\User;

use App\Dto\User\UserRegistrationDto;
use App\Service\User\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Validator\ValidatorHandler;

class RegistrationController
{
    private UserService $userService;
    private SerializerInterface $serializer;

    public function __construct(
        UserService $userService,
        SerializerInterface $serializer,
    ) {
        $this->userService = $userService;
        $this->serializer = $serializer;
    }

    #[Route('/user/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, ValidatorHandler $validator): JsonResponse
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            UserRegistrationDto::class,
            'json',
            ['groups' => ['users:write']]
        );

        $errors = $validator->validate($dto);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->register($dto);
        $response = json_decode($this->serializer->serialize($user, 'json', ['groups' => ['users']]), true);

        return new JsonResponse($response, JsonResponse::HTTP_CREATED);
    }
}