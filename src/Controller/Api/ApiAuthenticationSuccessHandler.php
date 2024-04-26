<?php

namespace App\Controller\Api;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ApiAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    // Méthode appellée lors d'une authentification réussie
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // On récuère l'utilisateur a partir du jeton
        $user = $token->getUser();
        
        // On renvoie une réponse JSON avec cette donnée
        $data = [
            'token' => $this->jwtManager->create($user),
            'user_id' => $user->getId(), // Include the user ID in the response
        ];

        return new JsonResponse($data);
    }
}