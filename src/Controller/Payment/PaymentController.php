<?php

namespace App\Controller\Payment;

use App\Service\Payment\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    public function __construct(private PaymentService $service)
    {
    }

    #[Route('/payment/confirm', name: 'payment_confirm', methods: ['POST'])]
    public function confirm(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $qrId = (string) ($data['qrId'] ?? '');
        $userId = (string) ($data['userId'] ?? '');
        $signature = (string) ($data['signature'] ?? '');
        if (!$qrId || !$userId || !$signature) {
            return new JsonResponse(['error' => 'Invalid payload'], 400);
        }
        try {
            $response = $this->service->confirmPayment($qrId, $userId, $signature);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => 'QR not found'], 404);
        }
        return new JsonResponse($response, 200);
    }
}
