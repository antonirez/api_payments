<?php

namespace App\Entity;

use DateTimeImmutable;

class Payment
{
    public const STATUS_SUCCESS = 'SUCCESS';
    public const STATUS_FAILED = 'FAILED';

    private string $transactionId;
    private string $qrId;
    private string $userId;
    private string $status;
    private DateTimeImmutable $createdAt;

    public function __construct(string $transactionId, string $qrId, string $userId, string $status)
    {
        $this->transactionId = $transactionId;
        $this->qrId = $qrId;
        $this->userId = $userId;
        $this->status = $status;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getQrId(): string
    {
        return $this->qrId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
