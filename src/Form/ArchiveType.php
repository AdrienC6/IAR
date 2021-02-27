<?php

namespace App\Form;

use App\Entity\Archive;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArchiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pdfFileName', FileType::class, [
                'label'=>'Fichier PDF',
                'mapped'=>false,
                'constraints'=>[
                    new File([
                        'maxSize'=>'1024k',
                        'mimeTypes'=> [
                            'application/pdf',
                            'application/w-pdf'
                        ],
                        'mimeTypesMessage'=>'Merci d\'importer un fichier PDF valude'
                    ])
                ]
            ])
            ->add('title', TextType::class, [
                'label'=>'Titre'
            ])
            ->add('year', NumberType::class, [
                'label'=>'Année de publication'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Archive::class,
        ]);
    }
}
