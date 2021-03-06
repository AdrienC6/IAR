<?php

namespace App\Controller\Admin;

use App\Entity\Archive;
use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Booking;
use App\Entity\Calendar;
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
            ->setTitle('InfoActivisme-Recherche')
            ->setFaviconPath('/images/favicon.png')
            ->disableUrlSignatures();
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToRoute('Site', 'fas fa-home', 'home'),

            MenuItem::linktoDashboard('Articles', 'far fa-newspaper')
                ->setPermission('ROLE_ADMIN'),

            MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class)
                ->setPermission('ROLE_ADMIN'),

            MenuItem::linkToCrud('Etiquettes', 'fas fa-tags', Tag::class)
                ->setPermission('ROLE_ADMIN'),

            MenuItem::linkToCrud('Calendrier', 'fas fa-calendar-alt', Calendar::class)
                ->setPermission('ROLE_ADMIN'),

            MenuItem::subMenu('Membres', 'fas fa-users')->setSubItems([
                MenuItem::linkToCrud('Membres', 'fas fa-user', User::class),
                MenuItem::linkToRoute('Import', 'fas fa-user-friends', 'csv')
            ]),

            MenuItem::subMenu('Archives', 'far fa-file')->setSubItems([
                MenuItem::linkToCrud('Liste', 'fas fa-file-pdf', Archive::class),
                MenuItem::linkToRoute('Import', 'far fa-file-pdf', 'pdf')
            ]),

        ];
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getUsername())
            ->displayUserName(true);
    }
}
