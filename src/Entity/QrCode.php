<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\QRCodeRepository;

/**
 * QRCode.
 */
#[ORM\Table(name: 'qr_code')]
#[ORM\Entity(repositoryClass: QRCodeRepository::class)]
class QRCode
{
    #[ORM\Column(name: 'id', type: 'guid')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['qr_code'])]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['qr_code'])]
    private float $amount;

    #[ORM\Column(type: 'string', length: 3)]
    #[Groups(['qr_code'])]
    private string $currency;

    #[ORM\Column(type: 'string')]
    #[Groups(['qr_code'])]
    private string $merchantId;

    public function __construct(float $amount, string $currency, string $merchantId)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->merchantId = $merchantId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function setMerchantId(string $merchantId): self
    {
        $this->merchantId = $merchantId;

        return $this;
    }
}
