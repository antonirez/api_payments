<?php

namespace App\Service\ApiKeys;

use App\Dto\ApiKey\ApiKeyCreateDto;
use App\Entity\ApiKeys;
use App\Service\Currency\CurrencyService;
use Doctrine\ORM\EntityManagerInterface;

class ApiKeysService
{
    private CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Create new client
     *
     * @param EntityManagerInterface $em
     * @param ApiKeyCreateDto $dto
     * @return ApiKeys
     * @throws \App\Validator\Exception\ValidationException
     * @throws \Random\RandomException
     */
    public function create(EntityManagerInterface $em, ApiKeyCreateDto $dto): ApiKeys
    {
        $currency = $this->currencyService->findOneEnabledById($em, $dto->currencyId);
        $apiKey = new ApiKeys();
        $apiKey->setCurrency($currency);
        $apiKey->setApiKey($this->apiKeyGenerator());
        $apiKey->setName($dto->name);
        $apiKey->setEnabled(true);
        $apiKey->setCreatedAt(new \DateTime());

        $em->persist($apiKey);
        $em->flush();

        return $apiKey;
    }

    /**
     * Api Key Generator
     *
     * @return string
     * @throws \Random\RandomException
     */
    private function apiKeyGenerator(): string
    {
        $seed = microtime(true) . bin2hex(random_bytes(16));
        return hash('sha256', $seed);
    }

}
