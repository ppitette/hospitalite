<?php

namespace App\Controller\Admin;

use App\Entity\Pelerinage;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator,
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(PelerinageCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hospitalité');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retourner sur le site', 'fa fa-undo', 'app.home');

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::subMenu('Pèlerinages', 'fas fa-photo-video')->setSubItems([
                MenuItem::linkToCrud('Pèlerinage', 'fas fa-photo-video', Pelerinage::class),
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Pelerinage::class)->setAction(Crud::PAGE_NEW),
            ]);

            yield MenuItem::subMenu('Comptes', 'fas fa-user')->setSubItems([
                MenuItem::linkToCrud('Tous les comptes', 'fas fa-user-friends', User::class),
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            ]);
        }
    }
}
