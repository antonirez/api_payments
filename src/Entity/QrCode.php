<?php

namespace App\Entity;

use DateTimeImmutable;

class QrCode
{
    private string $id;
    private float $amount;
    private string $currency;
    private string $merchantId;
    private DateTimeImmutable $expiresAt;

    public function __construct(string $id, float $amount, string $currency, string $merchantId, DateTimeImmutable $expiresAt)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->merchantId = $merchantId;
        $this->expiresAt = $expiresAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return [
            'qrId' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'merchantId' => $this->merchantId,
            'expiresAt' => $this->expiresAt->format(DATE_ATOM),
        ];
    }
}
