<?php

namespace App\Controller\ApiKey;

use App\Controller\CoreController;
use App\Dto\ApiKey\ApiKeyCreateDto;
use App\Service\ApiKeys\ApiKeysService;
use App\Validator\ValidatorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiKeyController extends CoreController
{
    #[Route('/api-key/create', name: 'app_api_key_create', methods: ['POST'])]
    public function create(Request $request, ValidatorHandler $validator, ApiKeysService $service): JsonResponse
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            ApiKeyCreateDto::class,
            'json',
            ['groups' => ['api_keys:write']]
        );

        $errors = $validator->validate($dto);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $apiKey = $service->create($this->em, $dto);
        $response = json_decode($this->serializer->serialize($apiKey, 'json', ['groups' => ['api_key:write']]), true);

        return new JsonResponse($response, JsonResponse::HTTP_CREATED);
    }
}