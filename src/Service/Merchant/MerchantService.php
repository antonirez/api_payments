<?php

namespace App\Service\Merchant;

use App\Dto\Merchant\MerchantCreateDto;
use App\Entity\ApiKeys;
use App\Entity\Merchants;
use Doctrine\ORM\EntityManagerInterface;

class MerchantService
{
    /**
     * Create new merchant
     *
     * @param EntityManagerInterface $em
     * @param ApiKeys $apiKey
     * @param MerchantCreateDto $dto
     * @return Merchants
     */
    public function create(EntityManagerInterface $em, ApiKeys $apiKey, MerchantCreateDto $dto): Merchants
    {
        $merchant = new Merchants($dto->config);
        $merchant->setApiKey($apiKey);
        $merchant->setName($dto->name);

        $em->persist($merchant);
        $em->flush();

        return $merchant;
    }

}