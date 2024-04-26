<?php

namespace App\Form\Back;

use App\Entity\Recipe;
use App\Entity\Content;
use App\Entity\Measure;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recipe', EntityType::class, [
            'class' => Recipe::class,
            'choice_label' => 'title',
            'multiple' => false,
            'label' => 'Recette :'
            ])
            ->add('quantity', Integertype::class, [
                'label' => 'Quantité :'
            ])
            ->add('measure', EntityType::class, [
                'class' => Measure::class,
                'choice_label' => 'type',
                'multiple' => false
                ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
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
            'data_class' => Content::class,
        ]);
    }
}
