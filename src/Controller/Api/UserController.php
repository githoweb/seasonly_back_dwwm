<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="app_api_user_")
 * @IsGranted("PUBLIC_ACCESS")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="subscribe", methods="POST")
     */
    public function subscribe(Request $request, UserRepository $userRepository, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        // On récupère les données user reçues 
        $json = $request->getContent();

        // deserialisation des données json pour obtenir un objet User
        $user = $serializer->deserialize($json, User::class, 'json');

        //on récupère l'email du User qui s'inscrit
        $email = $user->getEmail();

        // on vérifie si l'email récupéré existe déjà dans la table User
        $registeredUser = $userRepository->findOneBy(['email' => $email]);

        // si il existe on met le booléen à jour sur true
        if ($registeredUser) {
            if ($registeredUser->isNewsletter() === false) {
                $registeredUser->setNewsletter(true);
                $em->flush();
                // On retourne une réponse 
                return $this->json(['message' => 'Mise a jour de la newsletter reussie']);
            } else {
                // Sinon on retourne une réponse 
                return $this->json(['message' => 'Vous etes deja inscrit a la newsletter']);
            }
        } else {
            // s'il n'existe pas, on créé un nouvel enregistrement avec le booléen newsletter à true
            $user->setEmail($email);
            $user->setNewsletter(true);

            // on persist et on flush
            $em->persist($user);
            $em->flush();
            
            // On retourne une réponse 
            return $this->json($user, Response::HTTP_CREATED, ['message' => 'Inscription a la newsletter reussie']);
        }
    }

    /**
     * @Route("/user/{id}", name="unsubscribe", methods="DELETE", requirements={"id"="\d+"})
     */
    public function unsubscribe($id, EntityManagerInterface $em): JsonResponse
    {
        // On récupère l'objet User
        $user = $em->find(User::class, $id);

        // s'il n'existe pas = erreur 404
        if ($user === null)
        {
            return $this->json(['message' => 'User non trouve'],Response::HTTP_NOT_FOUND);
        }

        // on vérifie si le user est associé à un membre inscrit
        $member = $user->getMember();

        // si le user est aussi membre 
        if ($member !== null) {
            // et si le booléen est déjà à false, envoyer un message erreur
            if ($user->isNewsletter() === false) {
                return $this->json(['message' => 'Vous n\'etes pas inscrit a la newsletter']);
            }
            // sinon passer le booléen à false pour désinscrire le user à la newsletter
            $user->setNewsletter(false);
            $em->flush();
            return $this->json(['message' => 'Desinscription à la newsletter reussie']);
        }

        // si le user n'est pas membre, on peut le supprimer de la BDD
        // On supprime l'objet de la BDD grace à entity manager
        $em->remove($user);

        // On exécute la requete 
        $em->flush();

        //On revoie une réponse Json
        return $this->json(['message' => 'User supprime']);
    }
}