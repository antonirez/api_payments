<?php

namespace App\Controller;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QRController extends AbstractController
{
    public function __construct(private PaymentService $service)
    {
    }

    #[Route('/qr/create', name: 'qr_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $amount = (float) ($data['amount'] ?? 0);
        $currency = (string) ($data['currency'] ?? '');
        $merchantId = (string) ($data['merchantId'] ?? '');
        if (!$amount || !$currency || !$merchantId) {
            return new JsonResponse(['error' => 'Invalid payload'], 400);
        }
        $response = $this->service->createQR($amount, $currency, $merchantId);
        return new JsonResponse($response, 201);
    }

    #[Route('/qr/{qrId}', name: 'qr_details', methods: ['GET'])]
    public function details(string $qrId): JsonResponse
    {
        $qr = $this->service->getQR($qrId);
        if (!$qr) {
            return new JsonResponse(['error' => 'QR not found'], 404);
        }
        return new JsonResponse($qr, 200);
    }
}
