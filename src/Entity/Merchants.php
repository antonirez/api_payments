<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\MerchantsRepository;
use App\Entity\ApiKeys;

/**
 * Merchants.
 */
#[ORM\Entity(repositoryClass: MerchantsRepository::class)]
#[ORM\Table(name: 'merchants')]
#[ORM\HasLifecycleCallbacks]
class Merchants
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['merchants:read', 'merchants:write'])]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: ApiKeys::class, inversedBy: 'merchants', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'api_key_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['merchants:read'])]
    private ApiKeys $apiKey;

    #[ORM\Column(type: 'string', length: 50, nullable: false, unique: true)]
    #[Groups(['merchants:read', 'merchants:write'])]
    private string $name;

    /** Encrypted JSON stored in the same `config` column */
    #[ORM\Column(type: 'text', name: 'config')]
    private string $config;

    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Encrypts & stores the given config array as a JSON cipher in the `config` field.
     */
    public function setConfig(array $config): self
    {
        $json = json_encode($config);
        $key = base64_decode(getenv('MERCHANT_CONFIG_KEY'));
        $iv  = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $cipherText = openssl_encrypt($json, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        $this->config = base64_encode($iv . $cipherText);

        return $this;
    }

    /**
     * Decrypts the `config` field on-the-fly and returns it as an array.
     */
    #[Groups(['merchants:read'])]
    public function getConfig(): array
    {
        $data = base64_decode($this->config);
        $key = base64_decode(getenv('MERCHANT_CONFIG_KEY'));
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv        = substr($data, 0, $ivLength);
        $cipherText = substr($data, $ivLength);
        $json = openssl_decrypt($cipherText, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        return json_decode($json, true) ?? [];
    }
}
