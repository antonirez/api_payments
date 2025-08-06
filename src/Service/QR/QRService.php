<?php

namespace App\Service\QR;

use App\Dto\QR\QRCreateDto;
use App\Entity\ApiKeys;
use App\Entity\QrCode;
use App\Service\Currency\CurrencyService;
use App\Service\Merchant\MerchantService;
use App\Validator\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QRService
{
    private MerchantService $merchantService;
    private CurrencyService $currencyService;

    public function __construct(MerchantService $merchantService, CurrencyService $currencyService)
    {
        $this->merchantService = $merchantService;
        $this->currencyService = $currencyService;
    }

    /**
     * @param EntityManagerInterface $em
     * @param ApiKeys $apiKey
     * @param QRCreateDto $dto
     * @return array
     * @throws ValidationException
     */
    public function createQR(EntityManagerInterface $em, ApiKeys $apiKey, QRCreateDto $dto): array
    {
        $merchantByName = $this->merchantService->findByName($em, $apiKey, $dto->merchantName);
        if (empty($merchantByName)) {
            throw new ValidationException(serialize(['message' => 'Merchant not found', 'code' => Response::HTTP_BAD_REQUEST]));
        }

        $currency = $this->currencyService->findOneByAbbreviation($em, $dto->currency);
        if (empty($currency)) {
            throw new ValidationException(serialize(['message' => 'Currency not found', 'code' => Response::HTTP_NOT_FOUND]));
        }

        if ($currency !== $apiKey->getCurrency()) {
            throw new ValidationException(serialize(['message' => 'Currency not match with that merchant', 'code' => Response::HTTP_NOT_FOUND]));
        }

        $qrCode = new QrCode();
        $qrCode->setCurrency($currency->getAbbreviation());
        $qrCode->setAmount($dto->amount);
        $qrCode->setMerchantId($merchantByName->getId());
        $expiresAt = new \DateTimeImmutable('+30 minutes');
        $qrCode->setExpiresAt($expiresAt);
        $em->persist($qrCode);
        $em->flush();

        $payload = [
            'qrId'       => $qrCode->getId(),
            'currency'   => $qrCode->getCurrency(),
            'amount'     => $qrCode->getAmount(),
        ];

        $qrCodeString = json_encode($payload);

        return [
            'qrId'          => $qrCode->getId(),
            'qrCodeString'  => $qrCodeString,
            'expiresAt'     => $expiresAt->format(\DateTime::ATOM)
        ];
    }
}