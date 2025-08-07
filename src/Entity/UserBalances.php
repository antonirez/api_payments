<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\UserBalancesRepository;

/**
 * Users.
 */
#[ORM\Table(name: 'user_balance')]
#[ORM\Entity(repositoryClass: UserBalancesRepository::class)]
class UserBalances
{
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[Groups(['api_key:write', 'merchants:read'])]
    private $id;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Groups(['user_balance'])]
    private ?string $userId = null;

    #[ORM\ManyToOne(targetEntity: ApiKeys::class, inversedBy: 'user_balances', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'api_key_id', referencedColumnName: 'id', nullable: false)]
    private ApiKeys $apiKey;

    #[ORM\Column(type: 'float')]
    #[Groups(['user_balance'])]
    private float $balance;

    public function __construct(float $balance = 0.0)
    {
        $this->balance = $balance;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getApiKey(): ApiKeys
    {
        return $this->apiKey;
    }

    public function setApiKey(ApiKeys $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function add(float $amount): self
    {
        $this->balance += $amount;

        return $this;
    }
}
