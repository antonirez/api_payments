<?php

namespace App\EventSubscriber;

use App\Validator\Exception\ValidationException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'onKernelException')]
class ApiExceptionSubscriber
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if (! $e instanceof ValidationException) {
            return;
        }

        $errors = $e->getValidationErrors();
        // $errors debería venir algo como ['message'=>'User exists','code'=>400]
        $data = [
            'error'   => true,
            'message' => $errors['message'] ?? 'Validation failed',
        ];
        $status = $errors['code'] ?? JsonResponse::HTTP_BAD_REQUEST;

        $response = new JsonResponse($data, $status);
        $event->setResponse($response);
    }
}
