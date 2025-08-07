<?php

namespace App\Controller\QR;

use App\Controller\CheckApiKeyController;
use App\Controller\CoreController;
use App\Dto\QR\QRCreateDto;
use App\Service\QR\QRService;
use App\Validator\ValidatorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QRController extends CoreController implements CheckApiKeyController
{
    #[Route('/qr/create', name: 'qr_create', methods: ['POST'])]
    public function create(Request $request, ValidatorHandler $validator, QRService $service): JsonResponse
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            QRCreateDto::class,
            'json',
            ['groups' => ['qr_create:write']]
        );

        $errors = $validator->validate($dto);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $response = $service->createQR($this->em, $this->api_key, $dto);

        return new JsonResponse($response, Response::HTTP_CREATED);
    }

    #[Route('/qr/{qrId}', name: 'qr_details', methods: ['GET'])]
    public function details(string $qrId, QRService $service): JsonResponse
    {
        $qrCode = $service->getQRDetail($this->em, $qrId);

        $response = json_decode($this->serializer->serialize($qrCode, 'json', ['groups' => ['qr_code:read']]), true);

        return new JsonResponse($response, JsonResponse::HTTP_CREATED);
    }
}
