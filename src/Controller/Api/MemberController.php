<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Member;
use DateTimeImmutable;
use App\Form\Member1Type;
use App\Repository\MemberRepository;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/member")
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/", name="api_member_browse")
     */
    public function browse(MemberRepository $memberRepository): JsonResponse
    {
        // Préparation des données
        $membersList = $memberRepository->findAll();

        // On retourne les données des members
        return $this->json([
            'members' => $membersList,
        ], Response::HTTP_OK, [], ["groups" => "member"]);
    }

    /**
     * @Route("/add", name="api_member_create", methods="POST")
     * @IsGranted("PUBLIC_ACCESS")
     */
    public function create(EntityManagerInterface $entityManager,
    Request $request, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {       
        // On récupère les données user reçues 
        $json = $request->getContent();

        // Deserialization des données JSON pour obtenir un objet User
        $user = $serializer->deserialize($json, User::class, 'json');

        // On récupère l'email du User qui s'inscrit
        $email = $user->getEmail();

        // On vérifie si l'email récupéré existe déjà dans la table User
        $registeredUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$registeredUser) {
            // Création d'un nouveau User s'il n'existe pas
            $registeredUser = new User();
            $registeredUser->setEmail($email);
            $registeredUser->setNewsletter($user->isNewsletter());
            $registeredUser->setCreatedAt(new DateTimeImmutable());
            $entityManager->persist($registeredUser);
            $entityManager->flush();
        }

        // Création d'un nouveau membre
        $data = json_decode($json, true); // Convertit le JSON en tableau associatif
        $member = new Member();
        $member->setPseudo($data['pseudo']);
        $member->setPassword($passwordEncoder->encodePassword($member, $data['password']));
        $member->setRoles(['ROLE_MEMBER']);
        $member->setUser($registeredUser);
        $member->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($member);
        $entityManager->flush();

        // On retourne la validation de la création d'un member
        return $this->json(['message' => 'Inscription réussie'], JsonResponse::HTTP_CREATED, ["groups" => "member"]);
    }

    /**
     * @Route("/{id}", name="api_member_delete", methods="DELETE")
     * @IsGranted("ROLE_MEMBER")
     */
    public function delete($id, EntityManagerInterface $em): JsonResponse
    {
        // pour supprimer une ligne en BDD, il faut
        // récupérer l'entité depuis la BDD
        $member = $em->find(Member::class, $id);

        // On verifie si l'entité existe
        if ($member === null)
        {
            $errorMessage = [
                'message' => "Member not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // On retire l'entité
        $em->remove($member);

        // + flush pour exécuter les requêtes
        $em->flush();

        return $this->json("Membre supprimé");
    }

    /**
     * @Route("/{id}", name="api_member_read", methods="GET", requirements={"id"="\d+"})
     * @IsGranted("ROLE_MEMBER")
     */
    public function read($id, MemberRepository $memberRepository): JsonResponse
    {
        // On récupère l'entité grâce à son id 
        $member = $memberRepository->find($id);

        // On vérifie si l'entité existe
        if ($member === null)
        {
            $errorMessage = [
                'message' => "Member not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // On retourne l'entité avec ses propriété
        return $this->json([
            'member' => $member,
        ], Response::HTTP_OK, [], ["groups" => "member"]);
        
    }

    /**
     * @Route("/{id}", name="api_member_update", methods="PUT", requirements={"id"="\d+"})
     * @IsGranted("ROLE_MEMBER")
     */
    public function update($id, EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        // On récupère l'entité member grâce à son Id
        $member = $em->find(Member::class, $id);

        // On vérifie si l'entité existe
        if ($member === null)
        {
            $errorMessage = [
                'message' => "Member not found",
            ];
            return new JsonResponse($errorMessage, Response::HTTP_NOT_FOUND);
        }

        // on traite les données recues en Json
        $json = $request->getContent();

        // On sérialize les données reçu en Json
        $serializer->deserialize($json, Member::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $member]);

        $errorList = $validator->validate($member);
        if (count($errorList) > 0)
        {
            return $this->json($errorList, Response::HTTP_BAD_REQUEST);
        }
        // pas besoin de persist car on a fait un find
        // donc l'entité existe déja
        $em->flush();

        // on renvoit une réponse
        return $this->json($member, Response::HTTP_OK, [], ["groups" => 'member']);
    }
}