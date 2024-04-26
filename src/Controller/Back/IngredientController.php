<?php

namespace App\Controller\Back;

use App\Entity\Ingredient;
use App\Form\Back\IngredientType;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("back/ingredient", name="app_back_ingredient_")
 */
class IngredientController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(IngredientRepository $ingredientRepository): Response
    {
        // On retourne la liste des ingrédients présent en base de données
        return $this->render('back/ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, IngredientRepository $ingredientRepository): Response
    {
        // on créer une nouvelle entité Ingredient
        $ingredient = new Ingredient();

        // On récupère le form IngredientType
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        // On vérifie si le form est valide puis on l'ajoute en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientRepository->add($ingredient, true);

            // On redirige vers la page index des ingrédients
            return $this->redirectToRoute('app_back_ingredient_index', ['id' => $ingredient->getId()], Response::HTTP_SEE_OTHER);
        }

        // on retourne les données de l'ingrédient et du form
        return $this->renderForm('back/ingredient/create.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Ingredient $ingredient): Response
    {
        // On retourne les données d'un ingrédient
        return $this->render('back/ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * @Route("/{id}/update", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, Ingredient $ingredient, IngredientRepository $ingredientRepository): Response
    {
        // On récupère le form IngredientType
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        // On vérifie si les données sont valides puis on l'ajoute en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientRepository->add($ingredient, true);

            // On redirige vers la page index des ingrédients
            return $this->redirectToRoute('app_back_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        // On retourne les données de l'ingrédient et du form
        return $this->renderForm('back/ingredient/update.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Ingredient $ingredient, IngredientRepository $ingredientRepository): Response
    {
        // On vérifie le token de l'ingredient s'il est valide on supprime l'entité
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            $ingredientRepository->remove($ingredient, true);
        }

        // on redirige vers la page index des ingrédients
        return $this->redirectToRoute('app_back_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}