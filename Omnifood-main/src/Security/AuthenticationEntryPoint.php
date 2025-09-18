<?php
// src/Security/AuthenticationEntryPoint.php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): RedirectResponse
    {
        // Ajoutez un message flash personnalisé et redirigez vers la page de connexion
        $request->getSession()->getFlashBag()->add('note', 'Vous devez vous connecter pour accéder à cette page.');

        return new RedirectResponse($this->urlGenerator->generate('security_login'));
    }
}
