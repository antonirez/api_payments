<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\PaymentsRepository;

/**
 * Payments.
 */
#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
#[ORM\Table(name: 'payments')]
class Payments
{
    public const STATUS_SUCCESS  = 'SUCCESS';
    public const STATUS_FAILED   = 'FAILED';
    public const STATUS_CANCELED = 'CANCELED';

    #[ORM\Id]
    #[ORM\Column(name: 'transaction_id', type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[Groups(['payments:read', 'payments:write'])]
    private string $transactionId;

    #[ORM\OneToOne(targetEntity: QrCode::class)]
    #[ORM\JoinColumn(name: 'qr_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['payments:read', 'payments:write'])]
    private QrCode $qr;

    #[ORM\ManyToOne(targetEntity: Merchants::class)]
    #[ORM\JoinColumn(name: 'merchant_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['payments:read', 'payments:write'])]
    private Merchants $merchant;

    #[ORM\ManyToOne(targetEntity: UserBalance::class)]
    #[ORM\JoinColumn(name: 'user_balance_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['payments:read', 'payments:write'])]
    private UserBalance $userBalance;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['payments:read', 'payments:write'])]
    private string $status;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    #[Groups(['payments:read'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'expired_at', type: 'datetime_immutable', nullable: true)]
    #[Groups(['payments:read', 'payments:write'])]
    private ?DateTimeImmutable $expiredAt = null;

    public function __construct(
        string             $transactionId,
        QrCode             $qr,
        Merchants          $merchant,
        UserBalance        $userBalance,
        string             $status,
        ?DateTimeImmutable $expiredAt = null
    ) {
        $this->transactionId = $transactionId;
        $this->qr            = $qr;
        $this->merchant      = $merchant;
        $this->userBalance   = $userBalance;
        $this->status        = $status;
        $this->createdAt     = new DateTimeImmutable();
        $this->expiredAt     = $expiredAt;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getQr(): QrCode
    {
        return $this->qr;
    }

    public function setQr(QrCode $qr): self
    {
        $this->qr = $qr;
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

    public function getUserBalance(): UserBalance
    {
        return $this->userBalance;
    }

    public function setUserBalance(UserBalance $userBalance): self
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

    public function getExpiredAt(): ?DateTimeImmutable
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?DateTimeImmutable $expiredAt): self
    {
        $this->expiredAt = $expiredAt;
        return $this;
    }
}
