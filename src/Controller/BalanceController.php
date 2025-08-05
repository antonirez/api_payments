<?php

namespace App\Controller;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BalanceController extends AbstractController
{
    public function __construct(private PaymentService $service)
    {
    }

    #[Route('/balance/recharge', name: 'balance_recharge', methods: ['POST'])]
    public function recharge(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $userId = (string) ($data['userId'] ?? '');
        $amount = (float) ($data['amount'] ?? 0);
        $paymentMethod = (string) ($data['paymentMethod'] ?? '');
        if (!$userId || !$amount || !$paymentMethod) {
            return new JsonResponse(['error' => 'Invalid payload'], 400);
        }
        $response = $this->service->recharge($userId, $amount);
        return new JsonResponse($response, 200);
    }
}
