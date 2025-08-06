<?php

namespace App\Repository;


use App\Entity\Merchants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Merchants|null find($id, $lockMode = null, $lockVersion = null)
 * @method Merchants|null findOneBy(array $criteria, array $orderBy = null)
 * @method Merchants[]    findAll()
 * @method Merchants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Merchants::class);
    }
}