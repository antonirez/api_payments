<?php

namespace App\Controller\User;

use App\Controller\CheckApiKeyController;
use App\Controller\CoreController;
use App\Dto\User\BalanceRechargeDto;
use App\Service\User\UserBalanceService;
use App\Validator\ValidatorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BalanceController extends CoreController implements CheckApiKeyController
{
    #[Route('/balance/recharge', name: 'balance_recharge', methods: ['POST'])]
    public function recharge(Request $request, ValidatorHandler $validator, UserBalanceService $service): JsonResponse
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            BalanceRechargeDto::class,
            'json',
            ['groups' => ['balance:write']]
        );

        $errors = $validator->validate($dto);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $response = $service->recharge($this->em, $this->api_key, $dto);

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
