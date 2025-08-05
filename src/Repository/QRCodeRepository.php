<?php

namespace App\Repository;

use App\Entity\QRCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QRCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method QRCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method QRCode[]    findAll()
 * @method QRCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QRCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QRCode::class);
    }

}