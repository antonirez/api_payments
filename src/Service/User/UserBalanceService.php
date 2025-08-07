<?php

namespace App\Service\User;

use App\Entity\ApiKeys;
use App\Entity\UserBalances;
use Doctrine\ORM\EntityManagerInterface;

class UserBalanceService
{
    /**
     * @param EntityManagerInterface $em
     * @param ApiKeys $apiKey
     * @param string $userId
     * @return UserBalances|null
     */
    public function findOneBy(EntityManagerInterface $em, ApiKeys $apiKey, string $userId): UserBalances|null
    {
        return $em->getRepository(UserBalances::class)->findOneBy(['apiKey' => $apiKey, 'userId' => $userId]);
    }
}