<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    #[Route('/access-denied', name: 'access_denied')]
    public function accessDenied(): Response
    {
        $this->addFlash('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
        return $this->render('error/access_denied.html.twig');
    }
}
