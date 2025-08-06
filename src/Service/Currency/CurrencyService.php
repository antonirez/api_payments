<?php

namespace App\Service\Currency;

use App\Entity\Currencies;
use App\Validator\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CurrencyService
{
    public function findOneById(EntityManagerInterface $em, int $id): Currencies
    {
        $currency = $em->getRepository(Currencies::class)->findOneBy(['id' => $id, 'removed' => false]);

        if (!$currency) {
            throw new ValidationException(serialize(['message' => 'Currency not found', 'fields' => ['currency'], 'type' => '', 'code' => JsonResponse::HTTP_NOT_FOUND]));
        }

        return $currency;
    }

    public function findOneEnabledById(EntityManagerInterface $em, int $id): Currencies
    {
        $currency = $this->findOneById($em, $id);

        if (!$currency->getEnabled()) {
            throw new ValidationException(serialize(['message' => 'Currency not available', 'fields' => ['currency'], 'type' => '', 'code' => JsonResponse::HTTP_UNAUTHORIZED]));
        }

        return $currency;
    }

    public function findAll(EntityManagerInterface $em): array
    {
        return $em->getRepository(Currencies::class)->findBy(['enabled' => true, 'removed' => false]);
    }

    public function findOneByAbbreviation(EntityManagerInterface $em, string $abbreviation): ?Currencies
    {
        return $em->getRepository(Currencies::class)->findOneBy(['abbreviation' => $abbreviation, 'enabled' => true, 'removed' => false]);
    }

}