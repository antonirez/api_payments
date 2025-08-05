<?php

namespace App\Entity;

class UserBalance
{
    private string $userId;
    private float $balance;

    public function __construct(string $userId, float $balance = 0.0)
    {
        $this->userId = $userId;
        $this->balance = $balance;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function add(float $amount): void
    {
        $this->balance += $amount;
    }
}
