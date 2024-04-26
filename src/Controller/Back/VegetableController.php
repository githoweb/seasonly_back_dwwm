<?php

namespace App\Controller\Back;

use App\Entity\Vegetable;
use App\Form\Back\VegetableType;
use App\Repository\VegetableRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/vegetable", name="app_back_vegetable_")
 */
class VegetableController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(VegetableRepository $vegetableRepository): Response
    {
        // Préparation des données : liste des fruits et légumes
        $vegetablesList = $vegetableRepository->findAll();

        // On retourne les données des vegetables et la vue
        return $this->render('back/vegetable/index.html.twig', [
            'vegetables' => $vegetablesList,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods="GET", requirements={"id"="\d+"}) 
     */
    public function show($id, VegetableRepository $vegetableRepository): Response
    {
        // préparation des données : un vegetable par rapport à son id
        $vegetable = $vegetableRepository->find($id);
        $month = $vegetable->getMonths();
        $ingredient = $vegetable->getIngredient();
        $genre = $vegetable->getGenre();
        $botanical = $vegetable->getBotanical();

        // On retourne les données du vegetable et la vue
        return $this->render('back/vegetable/show.html.twig', [
            'vegetable' => $vegetable,
            'genre' => $genre,
            'botanical' => $botanical,
            'ingredient' => $ingredient,
            'month' => $month,   
        ]);
    }

    /**
     * @Route("/update/{id}", name="update", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function update(Request $request, Vegetable $vegetable, VegetableRepository $vegetableRepository): Response
    {
        // création d'un form de mise à jour avec les données du légume
        $form = $this->createForm(VegetableType::class, $vegetable);
        $form->handleRequest($request);

        // On vérifie que le form a été soumis et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On met à jour le vegetable dans le repository
            $vegetableRepository->add($vegetable, true);

            // on redirige vers la page détail du vegetable avec un code statut HTTP
            return $this->redirectToRoute('app_back_vegetable_show', ['id' => $vegetable->getId()], Response::HTTP_SEE_OTHER);
        }

        // On rend la vue de mise à jour avec le form et le légume MAJ
        return $this->renderForm('back/vegetable/update.html.twig', [
            'vegetable' => $vegetable,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, VegetableRepository $vegetableRepository): Response
    {
        // Création d'un nouvel objet Vegetable
        $vegetable = new Vegetable();

        // Création d'un form avec les données nécéssaires au vegetable
        $form = $this->createForm(VegetableType::class, $vegetable);
        $form->handleRequest($request);

        // Si le form est soumis et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On ajoute le nouveau légume dans le repository
            $vegetableRepository->add($vegetable, true);

            // On redirige vers la page de liste des vegetables avec le code statut HTTP
            return $this->redirectToRoute('app_back_vegetable_index', [], Response::HTTP_SEE_OTHER);
        }

        // On rend la vue de création d'un vegetable avec le form et le vegetable
        return $this->renderForm('back/vegetable/create.html.twig', [
            'vegetable' => $vegetable,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/vegetable/delete/{id}", name="delete", methods="POST", requirements={"id"="\d+"}) 
     */
    public function delete(Request $request, Vegetable $vegetable, VegetableRepository $vegetableRepository): Response
    {
        // Si le jeton CSRF est valide
        if ($this->isCsrfTokenValid('delete' . $vegetable->getId(), $request->request->get('_token'))) {
            // On supprime le vegetable du repository
            $vegetableRepository->remove($vegetable, true);

            // On ajoute un message success
            $this->addFlash('success', 'Vegetable supprimé');
        }

        // On redirige vers la page de liste des fruits et légumes avec le code HTTP
        return $this->redirectToRoute('app_back_vegetable_index', [], Response::HTTP_SEE_OTHER);
    }
}