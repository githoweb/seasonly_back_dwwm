<?php

namespace App\Form\Back;

use App\Entity\Meal;
use App\Entity\Recipe;
use DateTimeImmutable;
use App\Entity\Content;
use App\Entity\Measure;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
            ])  
            ->add('description', TextType::class, [
                'label' => 'Description :'
            ])
            ->add('image', TextType::class, [
                'label' => 'Image (URL) :'
            ])
            ->add('instruction', TextType::class, [
                'label' => 'Instruction'
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée :'
            ])
            ->add('serving', IntegerType::class, [
                'label' => 'Portion :'
            ])
            ->add('created_at', DateTimeType::class, array(
                'input' => 'datetime_immutable',
                'label' => 'Date de création :'
            ))
            ->add('updated_at', null, [
                'label' => 'Dernière modification :'
            ])
            ->add('meal', EntityType::class, [
                'class' => Meal::class,
                'choice_label' => 'name',
                'multiple' => false
            ])
            ->add('Sauvegarder', SubmitType::class, [
                'attr' => ['class' => 'btn custom-btn-add'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
