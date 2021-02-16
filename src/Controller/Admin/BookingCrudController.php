<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class BookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('begin_at', 'Début'),
            DateTimeField::new('end_at', 'Début'),
            TextField::new('title', 'Titre'),
            UrlField::new('facebookEvent', 'Facebook (URL'),
            AssociationField::new('category', 'Catégorie(s)'),
            AssociationField::new('tag', 'Etiquette(s)'),
            TextareaField::new('description', 'Description'),

        ];
    }
}
