<?php

namespace App\Controller\Back;

use App\Entity\Content;
use App\Form\Back\ContentType;
use App\Repository\ContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/content", name="app_back_content_")
 */
class ContentController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ContentRepository $contentRepository): Response
    {
        // On retourne la liste des contents
        return $this->render('back/content/index.html.twig', [
            'contents' => $contentRepository->getContentSortByRecipeName(),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, ContentRepository $contentRepository): Response
    {
        // On créer une nouvelle entité Content
        $content = new Content();
        // On récupère le form ContentType
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        // On vérifie si les données du form sont valides puis on l'ajoute en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $contentRepository->add($content, true);

            // On redirige vers la même page (content create)
            return $this->redirectToRoute('app_back_content_create', [], Response::HTTP_SEE_OTHER);
        }

        // On retourne l'entité et le form
        return $this->renderForm('back/content/create.html.twig', [
            'content' => $content,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Content $content): Response
    {
        // On retourne une entité Content grâce à son Id
        return $this->render('back/content/show.html.twig', [
            'content' => $content,
        ]);
    }

    /**
     * @Route("/{id}/update", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, Content $content, ContentRepository $contentRepository): Response
    {
        // On récupère le form ContentType
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        // On récupère les recettes associés au content
        $recipe = $content->getRecipe();

        // On vérifie si les données du form sont valides puis on l'ajoute en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $contentRepository->add($content, true);

            // On redirige vers la page de détail de la recette associé au content crée
            return $this->redirectToRoute('app_back_recipe_show', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
        }

        // On retourne l'entité content et le form
        return $this->renderForm('back/content/update.html.twig', [
            'content' => $content,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Content $content, ContentRepository $contentRepository): Response
    {
        // On récupère les recettes associés au content
        $recipe = $content->getRecipe();

        // On vérifie le token du content s'il est valide on supprime l'entité
        if ($this->isCsrfTokenValid('delete'.$content->getId(), $request->request->get('_token'))) {
            $contentRepository->remove($content, true);
        }

        // On redirige vers la page index des Contents
        return $this->redirectToRoute('app_back_recipe_show', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
    }
}
