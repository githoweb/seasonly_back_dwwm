<?php

namespace App\Controller\Api;

use App\Entity\Vegetable;
use App\Repository\VegetableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="app_api_vegetable_")
 */
class VegetableController extends AbstractController
{
    /**
     * @Route("/vegetable", name="list", methods="GET")
     * @IsGranted("PUBLIC_ACCESS")
     */
    public function list(VegetableRepository $vegetableRepository): JsonResponse
    {
        // Préparation des données
        $vegetablesList = $vegetableRepository->findAll();

        // On retourne les données des vegetables au format Json
        return $this->json([
            'vegetables' => $vegetablesList,
        ], Response::HTTP_OK, [], ["groups" => "vegetable"]);
    }

    /**
     * @Route("/vegetable/{id}", name="show", methods="GET", requirements={"id"="\d+"})
     * @IsGranted("PUBLIC_ACCESS")
     */
    public function show($id, VegetableRepository $vegetableRepository): JsonResponse
    {
        // préparation des données
        $vegetable = $vegetableRepository->find($id);

        // Message d'erreur si le vegetable n'existe pas 
        if ($vegetable === null) 
        {
            $errorMessage = [
                'message' => "Vegetable not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // On retourne les données du vegetable au format Json
        return $this->json([
            'vegetable' => $vegetable,
        ], Response::HTTP_OK, [], ["groups" => "vegetable"]);
    }

    /**
     * @Route("/vegetable/{id}", name="update", methods="PUT", requirements={"id"="\d+"})
     */
    public function update($id, EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        // On appelle la méthode find grace à l'entity manager, on recherche l'objet Vegetable
        $vegetable = $em->find(Vegetable::class, $id);

        // Si l'objet n'existe pas = erreur 404
        if ($vegetable === null)
        {
            $errorMessage = [
                'message' => "Vegetable not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // Traitement des données reçues au format Json

        // On récupère les données 
        $json = $request->getContent();

        // Deserialisation des données json, mise à jour des entités
        $serializer->deserialize($json, Vegetable::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $vegetable]);

        // Si erreur de validation = erreur 400
        $errorList = $validator->validate($vegetable);
        if (count($errorList) > 0)
        {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }

        // On enregistre les données
        $em->flush();

        // On retourne la réponse, on renvoie l'objet Vegetable
        return $this->json($vegetable, Response::HTTP_OK, [], ["groups" => "vegetable"]);
    }

    /**
     * @Route("/vegetable", name="create", methods="POST")
     */
    public function create(EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        // On récupère les données au format json
        $json = $request->getContent();

        // Deserialisation des données json pour obtenir un objet Vegetable
        $vegetable = $serializer->deserialize($json, Vegetable::class, 'json');

        // Validation de l'objet Vegetable, si erreur = erreur 400
        $errorList = $validator->validate($vegetable);
        if (count($errorList) > 0)
        {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }

        // On persist et enregistre les données
        $em->persist($vegetable);
        $em->flush();

        // On retourne une réponse 
        return $this->json($vegetable, Response::HTTP_CREATED, [], ["groups" => "vegetable"]);
    }

    /**
     * @Route("/vegetable/{id}", name="delete", methods="DELETE", requirements={"id"="\d+"}))
     */
    public function delete($id, EntityManagerInterface $em): JsonResponse
    {
        // On récupère l'objet Vegetable
        $vegetable = $em->find(Vegetable::class, $id);

        // s'il n'existe pas = erreur 404
        if ($vegetable === null)
        {
            $errorMessage = [
                'message' => "Vegetable not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // On supprime l'objet de la BDD grace à entity manager
        $em->remove($vegetable);

        // On exécute la requete 
        $em->flush();

        //On revoie une réponse Json
        return $this->json("Deleted");
    }
}