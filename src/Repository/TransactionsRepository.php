<?php

namespace App\Repository;

use App\Entity\Transactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transactions[]    findAll()
 * @method Transactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transactions::class);
    }

    public function getBalance($filters, $user, $apikey)
    {
        $list = $this->createQueryBuilder('t')->select('SUM(t.amount) as balance');
        $list->leftJoin('t.user', 'u');

        $list->andWhere('u.userId=:user');
        $list->setParameter('user', $user);
        $list->andWhere('u.apiKey=:api_key');
        $list->setParameter('api_key', $apikey);
        $list->andWhere('t.balance=TRUE');

        $query = $list->getQuery();
        $balance = 0;
        if ($query->getSingleResult()['balance'] != '') {
            $balance = $query->getSingleResult()['balance'];
        }

        return $balance;
    }
}
