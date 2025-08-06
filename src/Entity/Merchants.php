<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\MerchantsRepository;

/**
 * Merchant.
 */
#[ORM\Entity(repositoryClass: MerchantsRepository::class)]
#[ORM\Table(name: 'merchants')]
class Merchants
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['merchants:read', 'merchants:write'])]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['merchants:read', 'merchants:write'])]
    private string $name;

    #[ORM\Column(type: 'json')]
    #[Groups(['merchants:read', 'merchants:write'])]
    private array $config = [];

    public function __construct(string $name, array $config = [])
    {
        $this->name = $name;
        $this->config = $config;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }
}
