<?php

namespace App\Controller\Merchant;

use App\Controller\CheckApiKeyController;
use App\Controller\CoreController;
use App\Dto\Merchant\MerchantCreateDto;
use App\Service\Merchant\MerchantService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Validator\ValidatorHandler;

class MerchantController extends CoreController implements CheckApiKeyController
{
    #[Route('/merchant/create', name: 'app_merchant_create', methods: ['POST'])]
    public function create(Request $request, ValidatorHandler $validator, MerchantService $service): JsonResponse
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            MerchantCreateDto::class,
            'json',
            ['groups' => ['merchants:write']]
        );

        $errors = $validator->validate($dto);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $merchant = $service->create($this->em, $this->api_key, $dto);
        $response = json_decode($this->serializer->serialize($merchant, 'json', ['groups' => ['merchants:write']]), true);

        return new JsonResponse($response, Response::HTTP_CREATED);
    }
}