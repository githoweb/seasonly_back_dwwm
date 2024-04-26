<?php

namespace App\Form\Back;

use App\Entity\User;
use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On ajoute plusieurs champs avec differents types (TextType, ChoiceType)
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Nom d\'utilisateur'
            ])  
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_MEMBER' => 'ROLE_MEMBER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('created_at')
            ->add('updated_at')
            // ajout du bouton de soumission du form
            ->add('Sauvegarder', SubmitType::class, [
                'attr' => ['class' => 'btn custom-btn-add'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Options par défaut du formulaire
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}