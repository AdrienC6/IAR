<?php

namespace App\Controller\Admin;

use App\Entity\Calendar;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class CalendarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Calendar::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('start', 'Début'),
            DateTimeField::new('end', 'Fin'),
            BooleanField::new('all_day', 'Toute la journée'),
            TextField::new('title', 'Titre'),
            UrlField::new('url', 'Lien de l\'évènement'),
            AssociationField::new('categories', 'Catégorie(s)'),
            AssociationField::new('tags', 'Etiquette(s)'),
            TextareaField::new('description', 'Description'),
        ];
    }
}
