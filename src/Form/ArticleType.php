<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'required' => true
                ]
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Contenu'
            ])

            ->add('category', EntityType::class, [
                'label' => 'Catégorie(s)',
                'attr' => [
                    'required' => true
                ],
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'choice_attr' => [
                    'class' => 'form-choice-options'
                ]

            ])
            ->add('Tag', EntityType::class, [
                'label' => 'Etiquette(s)',
                'attr' => [
                    'required' => true
                ],
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' =>
                true,
                'choice_attr' => [
                    'class' => 'form-choice-options'
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
