<?php

namespace App\Service\Transaction;

use App\Dto\User\BalanceRechargeDto;
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
        $transaction->setAmount($payments->getAmount() * -1);
        $transaction->setPaymentMethod($payments->getMerchant()->getName());
        $transaction->setCreatedAt(new \DateTimeImmutable());

        $em->persist($transaction);

        return $transaction;
    }

    /**
     * @param EntityManagerInterface $em
     * @param UserBalances $user
     * @param BalanceRechargeDto $dto
     * @return void
     */
    public function createBalanceTransaction(EntityManagerInterface $em, UserBalances $user, BalanceRechargeDto $dto): void
    {
        $transaction = new Transactions();
        $transaction->setUser($user);
        $transaction->setAmount($dto->amount);
        $transaction->setPaymentMethod($dto->paymentMethod);
        $transaction->setCreatedAt(new \DateTimeImmutable());

        $em->persist($transaction);
    }

}