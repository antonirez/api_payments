<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\UserBalanceRepository;

/**
 * Users.
 */
#[ORM\Table(name: 'user_balance')]
#[ORM\Entity(repositoryClass: UserBalanceRepository::class)]
class UserBalance
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'guid')]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['user_balance'])]
    private ?string $id = null;

    #[ORM\Column(type: 'float')]
    #[Groups(['user_balance'])]
    private float $balance;

    public function __construct(float $balance = 0.0)
    {
        $this->balance = $balance;
    }

    public function getId(): ?string
    {
        return $this->id;
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
