<?php

// src/EventListener/AccessDeniedListener.php
namespace App\EventListener;

use App\Security\AccessDeniedHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedListener implements EventSubscriberInterface
{
    private AccessDeniedHandler $accessDeniedHandler;

    public function __construct(AccessDeniedHandler $accessDeniedHandler)
    {
        $this->accessDeniedHandler = $accessDeniedHandler;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 2],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // Vérifiez que l'exception est une AccessDeniedException
        if (!$exception instanceof AccessDeniedException) {
            // Gérez les autres types d'exceptions ici si nécessaire
            return;
        }

        $request = $event->getRequest();

        // Assurez-vous que la demande n'est pas nulle
        if (!$request instanceof Request) {
            // Gérer l'absence de demande si nécessaire
            return;
        }

        // Utilisez l'AccessDeniedHandler pour générer la réponse
        $response = $this->accessDeniedHandler->handle($request, $exception);

        // Définissez la réponse générée dans l'événement
        $event->setResponse($response);
    }
}
