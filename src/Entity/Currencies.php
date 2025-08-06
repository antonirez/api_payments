<?php

namespace App\Entity;

use App\Repository\CurrenciesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Currencies.
 */
#[ORM\Table(name: 'currencies')]
#[ORM\Entity(repositoryClass: CurrenciesRepository::class)]
class Currencies
{
    /**
     * @var int
     */
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[Groups(['api_key:write', 'merchants:read'])]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'zone', type: 'string', length: 100, nullable: true)]
    #[Groups(['api_key:write', 'merchants:read'])]
    private $zone;

    /**
     * @var string
     */
    #[ORM\Column(name: 'abbreviation', type: 'string', length: 5, nullable: true)]
    #[Groups(['api_key:write', 'merchants:read'])]
    private $abbreviation;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'enabled', type: 'integer', nullable: false)]
    #[Groups(['api_key:write', 'merchants:read'])]
    private $enabled;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'removed', type: 'boolean')]
    private $removed;

    /**
     * @var string
     */
    #[ORM\Column(name: 'symbol', type: 'string', length: 5, nullable: true)]
    #[Groups(['api_key:write', 'merchants:read'])]
    private $symbol;

    // --- Getters and setters ---//

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(?string $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(?string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getRemoved(): ?bool
    {
        return $this->removed;
    }

    public function setRemoved(bool $removed): self
    {
        $this->removed = $removed;

        return $this;
    }

    public function isRemoved(): ?bool
    {
        return $this->removed;
    }
}
