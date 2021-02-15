<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('words', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-item',
                    'placeholder' => 'Recherche...',
                    'required' => true
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => false,
                'class' => Category::class,
                'attr' => [
                    'required' => false
                ],
                'placeholder' => '--Catégorie--'
            ])
            ->add('tag', EntityType::class, [
                'label' => false,
                'class' => Tag::class,
                'attr' => [
                    'required' => false
                ],
                'placeholder' => '--Etiquette--'
            ])
            // ->add('Rechercher', SubmitType::class, [
            //     'attr' => [
            //         'formnovalidate' => 'formnovalidate'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['id' => 'searchForm']
        ]);
    }
}
