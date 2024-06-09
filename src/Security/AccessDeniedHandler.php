<?php

// src/Security/AccessDeniedHandler.php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        $errorMessage = $accessDeniedException->getMessage();

        // Générez une réponse HTML personnalisée avec un message d'erreur
        $htmlContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès interdit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #dc3545;
        }
        p {
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Accès interdit</h1>
        <p>$errorMessage</p>
    </div>
</body>
</html>
HTML;

        // Retournez la réponse personnalisée avec le code de statut HTTP 403
        return new Response($htmlContent, Response::HTTP_FORBIDDEN);
    }
}
