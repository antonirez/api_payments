<?php

namespace App\Service\Transaction;

use App\Entity\Payments;
use App\Entity\Transactions;
use App\Entity\UserBalances;
use Doctrine\ORM\EntityManagerInterface;

class TransactionService
{
    /**
     * @param EntityManagerInterface $em
     * @param UserBalances $user
     * @param Payments $payments
     * @return Transactions
     */
    public function createTransaction(EntityManagerInterface $em, UserBalances $user, Payments $payments): Transactions
    {
        $transaction = new Transactions();
        $transaction->setUser($user);
        $transaction->setPaymentId($payments->getId());
        $transaction->setAmount($payments->getAmount());
        $transaction->setPaymentMethod($payments->getMerchant()->getName());
        $transaction->setCreatedAt(new \DateTimeImmutable());

        $em->persist($transaction);

        return $transaction;
    }

}