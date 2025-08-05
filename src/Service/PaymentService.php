<?php

namespace App\Service;

use Symfony\Component\Uid\Uuid;

class PaymentService
{
    /** @var array<string, array> */
    private array $qrs = [];

    /** @var array<string, float> */
    private array $balances = [];

    public function createQR(float $amount, string $currency, string $merchantId): array
    {
        $qrId = Uuid::v4()->toRfc4122();
        $expiresAt = (new \DateTimeImmutable('+5 minutes'))->format(DATE_ATOM);
        $payload = [
            'qrId' => $qrId,
            'amount' => $amount,
            'currency' => $currency,
            'merchantId' => $merchantId,
            'expiresAt' => $expiresAt,
        ];
        $this->qrs[$qrId] = $payload;

        return [
            'qrId' => $qrId,
            'qrCodeString' => json_encode($payload, JSON_THROW_ON_ERROR),
            'expiresAt' => $expiresAt,
        ];
    }

    public function getQR(string $qrId): ?array
    {
        return $this->qrs[$qrId] ?? null;
    }

    public function confirmPayment(string $qrId, string $userId, string $signature): array
    {
        // In a real implementation, signature should be verified.
        if (!isset($this->qrs[$qrId])) {
            throw new \InvalidArgumentException('QR not found');
        }
        unset($this->qrs[$qrId]);
        return [
            'transactionId' => Uuid::v4()->toRfc4122(),
            'status' => 'SUCCESS',
        ];
    }

    public function recharge(string $userId, float $amount): array
    {
        $balance = ($this->balances[$userId] ?? 0) + $amount;
        $this->balances[$userId] = $balance;
        return [
            'userId' => $userId,
            'newBalance' => $balance,
        ];
    }
}
