<?php

namespace App\Dto\User;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class BalanceRechargeDto
{
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[Groups(['balance:write'])]
    public string $userId;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'numeric')]
    #[Groups(['balance:write'])]
    public float $amount;

    #[Assert\NotBlank]
    #[Groups(['balance:write'])]
    public string $paymentMethod;
}