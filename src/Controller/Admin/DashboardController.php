<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(ArticleCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Info-Activisme Recherche')
            ->setFaviconPath('/images/logotest.png')
            ->disableUrlSignatures();
    }

    public function configureMenuItems(): iterable
    {
        // MenuItem::section('Blog');
        // yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToRoute('Nouvel Article', 'far fa-newspaper', 'article_add');
        // yield MenuItem::linkToRoute('Tous', 'fas fa-newspaper', 'home');
        // yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class);
        // yield MenuItem::linkToCrud('Etiquettes', 'fas fa-tags', Tag::class);
        // yield MenuItem::linkToRoute('Site', 'fas fa-home', 'home');
        // yield MenuItem::linkToCrud('Etiquettes', 'fas fa-tags', Article::class);

        return [
            MenuItem::linktoDashboard('Articles', 'far fa-newspaper')
                ->setPermission('ROLE_ADMIN'),

            // MenuItem::linkToCrud('Articles', 'far fa-newspaper', Article::class),
            MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class)
                ->setPermission('ROLE_ADMIN'),

            MenuItem::linkToCrud('Etiquettes', 'fas fa-tags', Tag::class)
                ->setPermission('ROLE_ADMIN'),

            MenuItem::linkToCrud('Membres', 'fas fa-list', User::class)
                ->setPermission('ROLE_ADMIN')
                ->setAction('index'),

            MenuItem::linkToRoute('Site', 'fas fa-home', 'home'),

            // MenuItem::linkToCrud('Mon Profil', 'fa fa-user', User::class)
            //     ->setAction('detail')
            //     ->setEntityId($this->getUser()->getRoles()),

        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getUsername())
            ->displayUserName(true);
    }
}
