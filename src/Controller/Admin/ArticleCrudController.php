<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Repository\TagRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class ArticleCrudController extends AbstractCrudController
{
    public $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDateFormat("yyyyy.MMMMM.dd GGG hh:mm aaa")
            ->addFormTheme("forms/article.html.twig");
    }

    public function configureFields(string $pageName): iterable
    {
        $tags = $this->tagRepository->findAll();
        return [
            // TextField::new('title'),
            // TextareaField::new('text'),
            // TextField::new('twitter'),
            // TextField::new('facebook'),
            // ChoiceField::new('Tag')->setChoices(['ok' => 'ok']),
            'tag'
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $article = new Article();

        return $article;
    }
}
