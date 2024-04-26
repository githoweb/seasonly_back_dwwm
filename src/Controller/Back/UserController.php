<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\Back\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("back/user", name="app_back_user_")
 */
class UserController extends AbstractController 
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        // On retourne la liste des users
        return $this->render('back/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, UserRepository $userRepository): Response
    {
        // On créer une nouvelle entité
        $user = new User();
        // On récupère le form UserType
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // On vérifie le form puis on l'ajoute en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            // On redirige vers la page index des users
            return $this->redirectToRoute('app_back_user_index', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        // On retourne la page du formulaire de création d'un user
        return $this->renderForm('back/user/create.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/update", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, User $user, UserRepository $userRepository): Response
    {

        // On récupère le form UserType
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        // On vérifie le form puis on l'ajoute en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);


            // On redirige vers la page index du users
            return $this->redirectToRoute('app_back_user_index',[], Response::HTTP_SEE_OTHER);
        }

        // On retourne la page de modification d'un user
        return $this->renderForm('back/user/update.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        // On vérifie le token du user s'il est valide on supprime l'entité
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        // On redirige vers la page index des users
        return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
    }
}