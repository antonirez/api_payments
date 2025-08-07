<?php

namespace App\Service\QR;

use App\Dto\QR\QRCreateDto;
use App\Entity\ApiKeys;
use App\Entity\Payments;
use App\Entity\QrCode;
use App\Service\Currency\CurrencyService;
use App\Service\Merchant\MerchantService;
use App\Validator\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param string $qrId
     * @return Payments
     * @throws ValidationException
     */
    public function getQRDetail(EntityManagerInterface $em, string $qrId): Payments
    {
        $qrCode = $em->getRepository(Payments::class)->findOneBy(['qrId' => $qrId]);

        if (!$qrCode) {
            throw new ValidationException(serialize(['message' => 'QR not found', 'code' => Response::HTTP_BAD_REQUEST]));
        }

        return $qrCode;
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

        $payment = new Payments();
        $payment->setCurrency($currency);
        $payment->setAmount($dto->amount * -1);
        $payment->setMerchant($merchantByName);
        $payment->setCreatedAt(new \DateTimeImmutable());
        $expiresAt = new \DateTimeImmutable('+30 minutes');
        $payment->setExpiresAt($expiresAt);
        $payment->setStatus(Payments::STATUS_INITIATED);
        $payment->generateQrId();
        $payment->generateSignatureSeed();
        $em->persist($payment);

        $em->flush();

        $payload = [
            'qrId' => $payment->getQrId(),
        ];

        $qrCodeString = json_encode($payload);

        return [
            'qrCodeString' => $qrCodeString,
            'expiresAt' => $expiresAt->format(\DateTime::ATOM)
        ];
    }
}