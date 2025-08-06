<?php

namespace App\Entity;

use App\Entity\Currencies;
use App\Entity\Users;
use App\Repository\ApiKeysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * ApiKeys.
 */
#[ORM\Table(name: 'api_keys')]
#[ORM\Entity(repositoryClass: ApiKeysRepository::class)]
class ApiKeys
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(targetEntity: Users::class, mappedBy: 'apiKey', cascade: ['persist'], orphanRemoval: true)]
    private $users;

    #[ORM\ManyToOne(targetEntity: Currencies::class, inversedBy: 'apiKeys', cascade: ['persist'])]
    #[Groups(['api_key:write', 'merchants:read'])]
    private $currency;

    /**
     * @var string
     */
    #[ORM\Column(name: 'api_key', type: 'string', length: 255, unique: true)]
    private $apiKey;

    /**
     * @var string
     */
    #[ORM\Column(name: 'name', type: 'string', length: 100)]
    #[Groups(['api_key:write'])]
    private $name;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'enabled', type: 'boolean')]
    #[Groups(['api_key:write'])]
    private $enabled;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private $createdAt;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setApiKey($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getApiKey() === $this) {
                $user->setApiKey(null);
            }
        }

        return $this;
    }

    public function setCentralAddress(?string $centralAddress): self
    {
        $this->centralAddress = $centralAddress;

        return $this;
    }


    public function isEnabled(): ?bool
    {
        return $this->enabled;
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

}
