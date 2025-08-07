<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\PaymentsRepository;
use Symfony\Component\Uid\Uuid;

/**
 * Payments.
 */
#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
#[ORM\Table(name: 'payments')]
class Payments
{
    public const STATUS_INITIATED = 'INITIATED';
    public const STATUS_CONFIRMED = 'CONFIRMED';
    public const STATUS_EXPIRED = 'EXPIRED';
    public const STATUS_CANCELED = 'CANCELED';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['payments:write'])]
    private ?string $id = null;

    /**
     * Unique QR identifier, auto-generated UUID4
     */
    #[ORM\Column(name: 'qr_id', type: 'guid', unique: true)]
    #[Groups(['payments:write'])]
    private ?string $qrId = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['payments:read', 'payments:write'])]
    private float $amount;

    #[ORM\ManyToOne(targetEntity: Currencies::class)]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['payments:write', 'payments:read'])]
    private Currencies $currency;

    #[ORM\ManyToOne(targetEntity: Merchants::class)]
    #[ORM\JoinColumn(name: 'merchant_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['payments:read', 'payments:write'])]
    private Merchants $merchant;

    #[ORM\ManyToOne(targetEntity: UserBalances::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true)]
    #[Groups(['payments:write'])]
    private UserBalances $userBalance;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['payments:write'])]
    private string $status;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'expires_at', type: 'datetime_immutable', nullable: true)]
    #[Groups(['payments:read', 'payments:write'])]
    private ?DateTimeImmutable $expiresAt = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['payments:read', 'payments:write'])]
    private string $signatureSeed;

    public function generateQrId(): void
    {
        if (null === $this->qrId) {
            $this->qrId = Uuid::v4()->toRfc4122();
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getQrId(): string
    {
        return $this->qrId;
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

    public function getCurrency(): ?Currencies
    {
        return $this->currency;
    }

    public function setCurrency(?Currencies $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getMerchant(): Merchants
    {
        return $this->merchant;
    }

    public function setMerchant(Merchants $merchant): self
    {
        $this->merchant = $merchant;
        return $this;
    }

    public function getUserBalance(): UserBalances
    {
        return $this->userBalance;
    }

    public function setUserBalance(UserBalances $userBalance): self
    {
        $this->userBalance = $userBalance;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
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

    public function getSignatureSeed(): string
    {
        return $this->signatureSeed;
    }

    public function generateSignatureSeed(): void
    {
        $secret = base64_decode($_ENV['APP_SECRET']);
        $payload = sprintf('%s', $this->qrId);

        $mac = hash_hmac('sha256', $payload, $secret, true);

        $this->signatureSeed = base64_encode($mac);
    }

    /**
     * Verify the provided signature seed matches the stored one
     */
    public function verifySignature(string $receivedSeed): bool
    {
        $secret = base64_decode($_ENV['APP_SECRET']);
        $payload = sprintf('%s', $this->qrId);

        $expected = hash_hmac('sha256', $payload, $secret, true);

        return hash_equals($expected, base64_decode($receivedSeed));
    }
}
