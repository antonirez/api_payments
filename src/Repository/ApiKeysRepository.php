<?php

namespace App\Repository;

use App\Entity\ApiKeys;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiKeys|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiKeys|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiKeys[]    findAll()
 * @method ApiKeys[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiKeysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiKeys::class);
    }
}
