<?php

namespace App\Controller\Back;

use App\Entity\Member;
use App\Form\Back\MemberType;
use App\Repository\MemberRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/member", name="app_back_member_")
 */
class MemberController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(MemberRepository $memberRepository): Response
    {
        // On récupère la liste de tous les membres grace au repository
        $membersList = $memberRepository->findAll();

        // On renvoie la vue twig avec la liste des members
        return $this->render('back/member/index.html.twig', [
            'members' => $membersList, 
        ]);
    }

    /**
     * @Route("/update/{id}", name="update", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function update($id, Request $request, Member $member, MemberRepository $memberRepository): Response
    {
        // création d'un formulaire de mise à jour pour le member
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        // Si le formulaire est soumis et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On met à jour le member depuis le repository
            $memberRepository->add($member, true);

            // On redirige vers la page index avec un code statut HTTP
            return $this->redirectToRoute('app_back_member_index', [], Response::HTTP_SEE_OTHER);
        }

        // On rend la vue de mise à jour avec le form et le member
        return $this->renderForm('back/member/update.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods="POST", requirements={"id"="\d+"}) 
     */
    public function delete(Request $request, Member $member, MemberRepository $memberRepository): Response
    {
        // on vérifie le jeton CSRF avant de supprimer le membre
        if ($this->isCsrfTokenValid('delete' . $member->getId(), $request->request->get('_token'))) {
            
            // suppression du member depuis le repository
            $memberRepository->remove($member, true);

            // on ajoute un message success
            $this->addFlash('success', 'Membre supprimé');
        }

        // On redirige vers la page index avec le code HTTP
        return $this->redirectToRoute('app_back_member_index', [], Response::HTTP_SEE_OTHER);
    }
}