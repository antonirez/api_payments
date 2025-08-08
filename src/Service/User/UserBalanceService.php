<?php

namespace App\Service\User;

use App\Dto\User\BalanceRechargeDto;
use App\Entity\ApiKeys;
use App\Entity\UserBalances;
use App\Service\Merchant\MerchantService;
use App\Service\Transaction\TransactionService;
use App\Validator\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserBalanceService
{
    private MerchantService $merchantService;
    private TransactionService $transactionService;

    public function __construct(MerchantService $merchantService, TransactionService $transactionService)
    {
        $this->merchantService = $merchantService;
        $this->transactionService = $transactionService;
    }

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

    public function recharge(EntityManagerInterface $em, ApiKeys $apiKey, BalanceRechargeDto $dto)
    {
//        $merchantByName = $this->merchantService->findByName($em, $apiKey, $dto->paymentMethod);
//        if (empty($merchantByName)) {
//            throw new ValidationException(serialize(['message' => 'Merchant not found', 'code' => Response::HTTP_BAD_REQUEST]));
//        }

        $user = $this->findOneBy($em, $apiKey, $dto->userId);
        if (!$user) {
            throw new ValidationException(serialize(['message' => 'User not found', 'code' => Response::HTTP_BAD_REQUEST]));
        }

        $newBalance = $user->getBalance() + $dto->amount;
        $user->setBalance($newBalance);
        $em->persist($user);
        // Create balance transaction
        $this->transactionService->createBalanceTransaction($em, $user, $dto);
        $em->flush();

        return [
            'userId' => $user->getId(),
            'newBalance' => $newBalance,
        ];
    }

}