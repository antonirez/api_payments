<?php

namespace App\Controller\Payment;

use App\Controller\CheckApiKeyController;
use App\Controller\CoreController;
use App\Dto\Payment\PaymentConfirmDto;
use App\Service\Payment\PaymentService;
use App\Validator\ValidatorHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends CoreController implements CheckApiKeyController
{
    #[Route('/payment/confirm', name: 'payment_confirm', methods: ['POST'])]
    public function confirm(Request $request, ValidatorHandler $validator, PaymentService $service): JsonResponse
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            PaymentConfirmDto::class,
            'json',
            ['groups' => ['payment_confirm:write']]
        );

        $errors = $validator->validate($dto);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $response = $service->confirmPayment($this->em, $this->api_key, $dto);

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
