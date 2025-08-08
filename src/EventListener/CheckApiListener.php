<?php

namespace App\EventListener;

use App\Entity\ApiKeys;
use App\Controller\CheckApiKeyController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CheckApiListener
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function hasInterface(string $controller)
    {
        if (class_exists($controller)) {
            $reflect = new \ReflectionClass($controller);

            return $reflect->implementsInterface(CheckApiKeyController::class);
        }

        return null;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($this->hasInterface(...explode('::', $event->getRequest()->attributes->get('_controller'), 2))) {
            $headers = $event->getRequest()->headers->all();

            if (!isset($headers['api-key'])) {
                $data['type'] = 'required';
                $data['fields'] = ['Api-Key'];
                $data['message'] = 'Api key is required';

                $response = new JsonResponse($data, Response::HTTP_UNAUTHORIZED);

                return $event->setResponse($response);
            }

            $apiKey = $this->em->getRepository(ApiKeys::class)->findOneBy(['apiKey' => $headers['api-key'][0]]);

            if (!$apiKey) {
                $data['type'] = 'invalid';
                $data['fields'] = ['Api-Key'];
                $data['message'] = 'Api key invalid';

                $response = new JsonResponse($data, Response::HTTP_UNAUTHORIZED);

                return $event->setResponse($response);
            } else {
                if (!$apiKey->getEnabled()) {
                    $data['type'] = '';
                    $data['fields'] = [];
                    $data['message'] = 'Api key disabled';

                    $response = new JsonResponse($data, Response::HTTP_FORBIDDEN);

                    return $event->setResponse($response);
                }
            }

            $event->getRequest()->attributes->set('_api_key', $apiKey->getId());
        }
    }
}
