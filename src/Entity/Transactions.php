<?php

namespace App\Entity;

use App\Repository\TransactionsRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Transactions.
 */
#[ORM\Table(name: 'transactions')]
#[ORM\Entity(repositoryClass: TransactionsRepository::class)]
class Transactions
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    #[ORM\ManyToOne(targetEntity: UserBalances::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private UserBalances $userBalance;

    #[ORM\Column(name: 'amount', type: 'float', nullable: true)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private $amount;

    #[ORM\Column(name: 'payment_id', type: 'string', length: 255, nullable: true)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private $paymentId;

    #[ORM\Column(name: 'payment_method', type: 'string', length: 100, nullable: true)]
    #[Groups(['transaction:read', 'transaction:write'])]
    private $paymentMethod;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    #[Groups(['payments:read'])]
    private DateTimeImmutable $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?UserBalances
    {
        return $this->userBalance;
    }

    public function setUser(?UserBalances $userBalance): self
    {
        $this->userBalance = $userBalance;

        return $this;
    }

    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    public function setPaymentId(?string $paymentId): self
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

}
