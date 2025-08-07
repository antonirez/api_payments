<?php

namespace App\Dto\Payment;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentConfirmDto
{
    #[Assert\NotBlank(message: 'QR ID cannot be blank.')]
    #[Assert\Uuid(message: 'The QR ID must be a valid UUID.')]
    #[Groups(['payment_confirm:write'])]
    public string $qrId;

    #[Assert\NotBlank(message: 'User ID cannot be blank.')]
    #[Groups(['payment_confirm:write'])]
    public string $userId;

    #[Assert\NotBlank(message: 'Signature cannot be blank.')]
    #[Assert\Length(
        min: 1,
        max: 512,
        minMessage: 'Signature must not be empty.',
        maxMessage: 'Signature cannot exceed {{ limit }} characters.'
    )]
    #[Groups(['payment_confirm:write'])]
    public string $signature;
}
