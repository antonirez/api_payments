<?php

namespace App\Controller;

use App\Entity\ApiKeys;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

class CoreController extends AbstractController
{
    protected $em;
    protected $user;
    protected $api_key;
    protected $serializer;

    public function __construct(ManagerRegistry $doctrine, RequestStack $requestStack, SerializerInterface $serializer)
    {
        $this->em = $doctrine->getManager();
        $this->serializer = $serializer;

        if ($apiKey = $requestStack->getCurrentRequest()->attributes->get('_api_key')) {
            $this->api_key = $this->em->getReference(ApiKeys::class, $apiKey);
        }
    }
}
