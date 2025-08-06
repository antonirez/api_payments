<?php

namespace App\Dto\QR;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class QRCreateDto
{
    #[Assert\NotNull(message: 'Amount cannot be null.')]
    #[Assert\Type(
        type: 'numeric',
        message: 'Amount must be a number (can be positive or negative, including decimals).'
    )]
    #[Groups(['qr_create:write'])]
    public float $amount;

    #[Assert\NotBlank(message: 'Currency cannot be blank.')]
    #[Groups(['qr_create:write'])]
    public string $currency;

    #[Assert\NotBlank(message: 'Merchant name cannot be blank.')]
    #[Groups(['qr_create:write'])]
    public string $merchantName;

    public function __construct(float $amount = 0.0, string $currency = '', string $merchantName = '')
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->merchantName = $merchantName;
    }
}
