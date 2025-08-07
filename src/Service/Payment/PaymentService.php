<?php

namespace App\Service\Payment;

use App\Dto\Payment\PaymentConfirmDto;
use App\Entity\ApiKeys;
use App\Entity\Payments;
use App\Entity\UserBalances;
use App\Service\Transaction\TransactionService;
use App\Service\User\UserBalanceService;
use App\Validator\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class PaymentService
{
    private UserBalanceService $userBalanceService;
    private TransactionService $transactionService;

    public function __construct(UserBalanceService $userBalanceService, TransactionService $transactionService)
    {
        $this->userBalanceService = $userBalanceService;
        $this->transactionService = $transactionService;
    }

    private function getPaymentToConfirm(EntityManagerInterface $em, string $qrId, string $signatureSeed): Payments
    {
        $payment = $em->getRepository(Payments::class)->findOneBy(['qrId' => $qrId, 'signatureSeed' => $signatureSeed, 'status' => Payments::STATUS_INITIATED]);

        if (!$payment) {
            throw new ValidationException(serialize(['message' => 'Payment not found', 'code' => Response::HTTP_BAD_REQUEST]));
        }

        return $payment;

    }

    public function confirmPayment(EntityManagerInterface $em, ApiKeys $apiKey, PaymentConfirmDto $dto): array
    {
        $payment = $this->getPaymentToConfirm($em, $dto->qrId, $dto->signature);
        if (!$payment->verifySignature($dto->signature)) {
            throw new ValidationException(serialize(['message' => 'Invalid payment signature', 'code' => Response::HTTP_BAD_REQUEST]));
        }

        $user = $this->userBalanceService->findOneBy($em, $apiKey, $dto->userId);
        if (!$user) {
            $user = new UserBalances();
            $user->setUserId($dto->userId);
            $user->setApiKey($apiKey);
            $user->setBalance($payment->getAmount());
        }

        $payment->setUserBalance($user);
        $payment->setStatus(Payments::STATUS_CONFIRMED);
        $balance = $user->getBalance() - $payment->getAmount();
        // Check balance
        if ($balance < 0) {
            throw new ValidationException(serialize(['message' => 'There is not enough balance', 'code' => Response::HTTP_BAD_REQUEST]));
        }

        $user->setBalance($balance);

        // Create transaction
        $transaction = $this->transactionService->createTransaction($em, $user, $payment);

        $em->persist($user);
        $em->persist($payment);
        $em->flush();

        return [
            'transactionId' => $transaction->getId(),
            'status' => $payment->getStatus(),
        ];
    }

}
