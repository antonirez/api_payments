<?php

namespace App\EventListener;

use App\Entity\ApiKeys;
use App\Controller\CheckApiKeyController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

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

            $request = $event->getRequest();
            $langClient = null;

            if (!isset($headers['api-key'])) {
                $data['type'] = 'required';
                $data['fields'] = ['Api-Key'];
                $data['message'] = 'Api key is required';

                $response = new JsonResponse($data, 401);

                return $event->setResponse($response);
            }

            $apiKey = $this->em->getRepository(ApiKeys::class)->findOneBy(['apiKey' => $headers['api-key'][0]]);

            if ($apiKey) {
                $langs = array_filter($apiKey->getLanguages()->toArray(), function ($lang) use ($request) {
                    return $lang->getCode() === $request->getLocale();
                });

                if (!empty($langs)) {
                    $langClient = reset($langs)->getCode();
                }

                if (!$langClient && $apiKey->getDefaultLanguage()) {
                    $langClient = $apiKey->getDefaultLanguage()->getCode();
                }
            }

            if (!$langClient) {
                $langClient = $request->getLocale();
            }

            $request->attributes->set('_default_lang', $langClient);

            if (!$apiKey) {
                $data['type'] = 'invalid';
                $data['fields'] = ['Api-Key'];
                $data['message'] = 'Api key invalid';

                $response = new JsonResponse($data, 401);

                return $event->setResponse($response);
            } else {
                if (!$apiKey->getEnabled()) {
                    $data['type'] = '';
                    $data['fields'] = [];
                    $data['message'] = 'Api key disabled';

                    $response = new JsonResponse($data, 403);

                    return $event->setResponse($response);
                } else {
                    if (!$apiKey->getEndpoint() && !preg_match('/api_v._set_config/i', $event->getRequest()->attributes->get('_route'))) {
                        $data['type'] = '';
                        $data['fields'] = [];
                        $data['message'] = $this->translator->trans('first_webhook');

                        $response = new JsonResponse($data, 401);

                        return $event->setResponse($response);
                    }

                    if (!$apiKey->getCentralAddress() && !preg_match('/api_v._set_config/i', $event->getRequest()->attributes->get('_route'))) {
                        $data['type'] = '';
                        $data['fields'] = [];
                        $data['message'] = $this->translator->trans('first_central_address');

                        $response = new JsonResponse($data, 401);

                        return $event->setResponse($response);
                    }
                }
            }

            $event->getRequest()->attributes->set('_api_key', $apiKey->getId());
        }
    }
}
