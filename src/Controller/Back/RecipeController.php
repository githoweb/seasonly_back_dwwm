<?php

namespace App\Controller\Back;

use App\Entity\Recipe;
use App\Form\Back\RecipeType;
use App\Repository\RecipeRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/recipe", name="app_back_recipe_")
 */
class RecipeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(RecipeRepository $recipeRepository, ContentRepository $contentRepository): Response
    {
        // Préparation des données
        $recipesList = $recipeRepository->findAll();

        // On retourne les données des recipes
        return $this->render('back/recipe/index.html.twig', [
            'recipes' => $recipesList,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods="GET", requirements={"id"="\d+"}) 
     */
    public function show($id, RecipeRepository $recipeRepository): Response
    {
        // Préparation des données
        $recipe = $recipeRepository->find($id);
        // Récupération des table associé aux recettes
        $content = $recipe->getContents();
        $meal = $recipe->getMeal();

        // On retourne les données de recipes, content et meal
        return $this->render('back/recipe/show.html.twig', [
            'recipe' => $recipe,
            'contents' => $content,
            'meal' => $meal, 
        ]);
    }

    /**
     * @Route("/{id}/update", name="update", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function update(Request $request, Recipe $recipe, RecipeRepository $recipeRepository): Response
    {
        // On récupère le form RecipeType
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        // On vérifie si les données du form sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            $recipeRepository->add($recipe, true);

            // On redirige vers le détail de la recette créée
            return $this->redirectToRoute('app_back_recipe_show', ['id' => $recipe->getId()], Response::HTTP_SEE_OTHER);
        }

        // On retourne les données de recipe et du form
        return $this->renderForm('back/recipe/update.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, RecipeRepository $recipeRepository): Response
    {
        // On créer une nouvelle entité recipe
        $recipe = new Recipe();
        // On récupère le form RecipeType
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        // On vérifie si les données du form sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            $recipeRepository->add($recipe, true);

            // Si elles sont valides on redirige vers la page de création des contents
            return $this->redirectToRoute('app_back_content_create', [], Response::HTTP_SEE_OTHER);
        }

        // On retourne les données des recipes et du form
        return $this->renderForm('back/recipe/create.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods="POST", requirements={"id"="\d+"}) 
     */
    public function delete(Request $request, Recipe $recipe, RecipeRepository $recipeRepository): Response
    {
        // On vérifie le token de recipe s'il est valide on supprime l'entité
        if ($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->request->get('_token'))) {
            $recipeRepository->remove($recipe, true);
            $this->addFlash('success', 'Recette supprimé');
        }

        // On redirige vers la page index des recipes
        return $this->redirectToRoute('app_back_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}