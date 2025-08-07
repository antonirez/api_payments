<?php

namespace App\Controller\User;

use App\Controller\CoreController;
use App\Dto\User\UserRegistrationDto;
use App\Service\User\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Validator\ValidatorHandler;

class RegistrationController extends CoreController
{
    #[Route('/user/register', name: 'app_user_register', methods: ['POST'])]
    public function register(Request $request, ValidatorHandler $validator, UserService $userService): JsonResponse
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            UserRegistrationDto::class,
            'json',
            ['groups' => ['users:write']]
        );

        $errors = $validator->validate($dto);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $user = $userService->register($this->em, $dto);
        $response = json_decode($this->serializer->serialize($user, 'json', ['groups' => ['users']]), true);

        return new JsonResponse($response, Response::HTTP_CREATED);
    }
}