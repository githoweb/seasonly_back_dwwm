<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/api/logout", name="app_api_logout")
     * @IsGranted("ROLE_MEMBER")
     * 
     */
    public function logout(): void
    {
        // ce controller peut etre vide
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}