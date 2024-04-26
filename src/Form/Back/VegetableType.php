<?php

namespace App\Form\Back;

use App\Entity\Genre;
use App\Entity\Month;
use App\Entity\Botanical;
use App\Entity\Vegetable;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VegetableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On ajoute plusieurs champs avec differents types (TextType, ChoiceType)
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom'
            ])  
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('image', TextType::class, [
                'label' => 'Image'
            ])
            ->add('benefits', TextType::class, [
                'label' => 'Bénéfices apportés'
            ])
            ->add('local', ChoiceType::class, [
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'multiple' => false,
                'expanded' => true
            ])
            ->add('conservation', TextType::class, [
                'label' => 'Conservation'
            ])
            ->add('months', EntityType::class, [
                'class' => Month::class,
                'label' => 'Mois associés',
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('botanical', EntityType::class, [
                'class' => Botanical::class,
                'label' => 'Identité en botanique',
                'choice_label' => 'name'
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'label' => 'Genre',
                'choice_label' => 'name',
                'multiple' => false
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'label' => 'Fruit ou légume racine',
                'choice_label' => 'name'
            ])
            ->add('created_at')
            ->add('updated_at')
            // Bouton de soumission du form
            ->add('Sauvegarder', SubmitType::class, [
                'attr' => ['class' => 'btn custom-btn-add'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configuration par défaut
        $resolver->setDefaults([
            'data_class' => Vegetable::class,
        ]);
    }
}
