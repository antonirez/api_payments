<?php

namespace App\Controller\User;

use App\Dto\User\BalanceRechargeDto;
use App\Service\Payment\PaymentService;
use App\Validator\ValidatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BalanceController extends AbstractController
{
    private PaymentService $paymentService;
    private SerializerInterface $serializer;
    public function __construct(
        PaymentService $paymentService,
        SerializerInterface $serializer,
    ) {
        $this->paymentService = $paymentService;
        $this->serializer = $serializer;
    }

    #[Route('/balance/recharge', name: 'balance_recharge', methods: ['POST'])]
    public function recharge(Request $request, ValidatorHandler $validator): JsonResponse
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

        $response = $this->paymentService->recharge($dto);
        return new JsonResponse($response, Response::HTTP_OK);
    }
}
