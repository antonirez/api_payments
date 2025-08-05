<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\PaymentsRepository;

/**
 * Payments.
 */
#[ORM\Table(name: 'payments')]
#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
class Payments
{
    public const STATUS_SUCCESS = 'SUCCESS';
    public const STATUS_FAILED = 'FAILED';

    #[ORM\Id]
    #[ORM\Column(name: 'transaction_id', type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[Groups(['payments'])]
    private string $transactionId;

    #[ORM\OneToOne(targetEntity: QRCode::class)]
    #[ORM\JoinColumn(name: 'qr_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['payments'])]
    private QRCode $qr;

    #[ORM\ManyToOne(targetEntity: Clients::class)]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['payments'])]
    private Clients $client;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['payments'])]
    private string $status;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    #[Groups(['payments'])]
    private DateTimeImmutable $createdAt;

    public function __construct(string $transactionId, QRCode $qr, Clients $client, string $status)
    {
        $this->transactionId = $transactionId;
        $this->qr = $qr;
        $this->client = $client;
        $this->status = $status;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getQr(): QRCode
    {
        return $this->qr;
    }

    public function setQr(QRCode $qr): self
    {
        $this->qr = $qr;

        return $this;
    }

    public function getClient(): Clients
    {
        return $this->client;
    }

    public function setClient(Clients $client): self
    {
        $this->client = $client;

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
}
