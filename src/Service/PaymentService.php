<?php

namespace App\Service;

use App\Entity\Payment;
use App\Entity\QrCode;
use App\Entity\UserBalance;
use Symfony\Component\Uid\Uuid;

class PaymentService
{
    /** @var array<string, QrCode> */
    private array $qrs = [];

    /** @var array<string, UserBalance> */
    private array $balances = [];

    /** @var array<string, Payment> */
    private array $payments = [];

    public function createQR(float $amount, string $currency, string $merchantId): array
    {
        $qrId = Uuid::v4()->toRfc4122();
        $expiresAt = new \DateTimeImmutable('+5 minutes');
        $qr = new QrCode($qrId, $amount, $currency, $merchantId, $expiresAt);
        $this->qrs[$qrId] = $qr;

        return [
            'qrId' => $qrId,
            'qrCodeString' => json_encode($qr->toPayload(), JSON_THROW_ON_ERROR),
            'expiresAt' => $expiresAt->format(DATE_ATOM),
        ];
    }

    public function getQR(string $qrId): ?array
    {
        return isset($this->qrs[$qrId]) ? $this->qrs[$qrId]->toPayload() : null;
    }

    public function confirmPayment(string $qrId, string $userId, string $signature): array
    {
        // In a real implementation, signature should be verified.
        if (!isset($this->qrs[$qrId])) {
            throw new \InvalidArgumentException('QR not found');
        }
        unset($this->qrs[$qrId]);
        $transactionId = Uuid::v4()->toRfc4122();
        $payment = new Payment($transactionId, $qrId, $userId, Payment::STATUS_SUCCESS);
        $this->payments[$transactionId] = $payment;

        return [
            'transactionId' => $transactionId,
            'status' => $payment->getStatus(),
        ];
    }

    public function recharge(string $userId, float $amount): array
    {
        $balance = $this->balances[$userId] ?? new UserBalance($userId);
        $balance->add($amount);
        $this->balances[$userId] = $balance;

        return [
            'userId' => $userId,
            'newBalance' => $balance->getBalance(),
        ];
    }
}
