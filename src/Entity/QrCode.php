<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\QRCodeRepository;

/**
 * QRCode.
 */
#[ORM\Table(name: 'qr_code')]
#[ORM\Entity(repositoryClass: QRCodeRepository::class)]
class QrCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['qr_code:write'])]
    private ?string $id = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['qr_code:read', 'qr_code:write'])]
    private float $amount;

    #[ORM\Column(type: 'string', length: 3)]
    #[Groups(['qr_code:read', 'qr_code:write'])]
    private string $currency;

    #[ORM\Column(type: 'string')]
    #[Groups(['qr_code:read', 'qr_code:write'])]
    private string $merchantId;

    #[ORM\Column(name: 'expires_at', type: 'datetime_immutable', nullable: true)]
    #[Groups(['qr_code:read', 'qr_code:write'])]
    private ?DateTimeImmutable $expiresAt = null;

    public function getId(): ?string
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

    public function getExpiresAt(): ?DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTimeImmutable $expiresAt): self
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }
}
