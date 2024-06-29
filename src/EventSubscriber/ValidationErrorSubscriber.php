<?php

namespace App\EventSubscriber;

use App\Exception\ValidationException;
use App\Service\ValidationErrorTransformer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ValidationErrorSubscriber implements EventSubscriberInterface
{
    private ValidationErrorTransformer $errorTransformer;

    public function __construct(ValidationErrorTransformer $errorTransformer)
    {
        $this->errorTransformer = $errorTransformer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $errorsArray = $this->errorTransformer->transform($exception->getViolations());
            $response = new JsonResponse(['errors' => $errorsArray], 400);
            $event->setResponse($response);
        }
    }
}
