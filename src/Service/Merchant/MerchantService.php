<?php

namespace App\Service\Merchant;

use App\Dto\Merchant\MerchantCreateDto;
use App\Entity\ApiKeys;
use App\Entity\Merchants;
use App\Validator\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MerchantService
{
    public function findByName(EntityManagerInterface$em, ApiKeys $apiKey, string $name): ?Merchants
    {
        return $em->getRepository(Merchants::class)->findOneBy(['name' => $name, 'apiKey' => $apiKey]);
    }

    /**
     * Create new merchant
     *
     * @param EntityManagerInterface $em
     * @param ApiKeys $apiKey
     * @param MerchantCreateDto $dto
     * @return Merchants
     * @throws ValidationException
     */
    public function create(EntityManagerInterface $em, ApiKeys $apiKey, MerchantCreateDto $dto): Merchants
    {
        $merchantByName = $this->findByName($em, $apiKey, $dto->name);
        if (!empty($merchantByName)) {
            throw new ValidationException(serialize(['message' => 'Merchant exists', 'code' => JsonResponse::HTTP_BAD_REQUEST]));
        }

        $merchant = new Merchants($dto->config);
        $merchant->setApiKey($apiKey);
        $merchant->setName($dto->name);

        $em->persist($merchant);
        $em->flush();

        return $merchant;
    }

}