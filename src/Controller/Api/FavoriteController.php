<?php

namespace App\Controller\Api;

use App\Entity\Member;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/favorite", name="app_api_favorite_")
 * @IsGranted("ROLE_MEMBER")
 */
class FavoriteController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function list(): JsonResponse
    {
        /** @var Member $member */
        $member = $this->getUser();

        // Récupérer les recettes mises en favoris par le membre
        $favorites = $member->getRecipes();

        // on renvoit une réponse
        return $this->json([
            'favorites' => $favorites,
        ], Response::HTTP_OK, [], ["groups" => "recipe"]);
    }

    /**
     * @Route("/add/{id}", name="add", methods="POST", requirements={"id"="\d+"})
     */
    public function add($id, EntityManagerInterface $em): JsonResponse
    {
        /** @var Member $member */
        $member = $this->getUser();
        
        // Récupérer la recette à ajouter aux favoris
        $recipe = $em->getRepository(Recipe::class)->find($id);

        // Vérifier si la recette existe
        if (!$recipe) {
            return $this->json(['message' => 'Recette non trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Ajouter la recette aux favoris du membre
        $member->addRecipe($recipe);

        // Enregistrer les modifications dans la base de données
        $em->flush();

        // retourner un message
        return $this->json(['message' => 'Recette ajoutée en favoris !'], JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods="DELETE", requirements={"id"="\d+"}))
     */
    public function delete($id, EntityManagerInterface $em): JsonResponse
    {
        /** @var Member $member */
        $member = $this->getUser();
        
        // Récupérer la recette à ajouter aux favoris
        $recipe = $em->getRepository(Recipe::class)->find($id);

        // Vérifier si la recette existe
        if (!$recipe) {
            return $this->json(['message' => 'Recette non trouvée'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Supprimer la recette des favoris du membre
        $member->removeRecipe($recipe);

        // Enregistrer les modifications dans la base de données
        $em->flush();

        // retourner un message
        return $this->json(['message' => 'Recette supprimée des favoris'], JsonResponse::HTTP_OK);
    }
}