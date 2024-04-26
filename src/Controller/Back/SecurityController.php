<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/back", name="app_back_")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // S'il y a une erreur de connexion on la récupère
        $error = $authenticationUtils->getLastAuthenticationError();

        // On récupère le dernier nom saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // En renvoi la vue de la page de connexion avec les données préparées plus haut
        return $this->render('back/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        // Ce controller peut etre vide
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}