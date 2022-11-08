<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        //généeze une url afin d'acceder a la page d'acccueil de crud des articles

        $url = $adminUrlGenerator -> setController(ArticleCrudController::class)
            ->generateUrl();
        return $this->redirect('http://127.0.0.1:8000/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CArticleCrudController');



    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin Symfony Blog');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToUrl('Retour', 'fa fa-close',$this->generateUrl('app_accueil'));
//créer section :
        yield MenuItem::section('articles');
        //créer sous menu
        yield MenuItem::subMenu('Actions', 'fa fa-bars')
            ->setSubItems([
                MenuItem::linkToCrud("Lister article", 'fas fa-eye',Article::class)
                    ->setAction(Crud::PAGE_INDEX)->setDefaultSort(['createdAt'=>'DESC']),
                MenuItem::linkToCrud("Ajouter article", 'fas fa-plus',Article::class)
                ->setAction(Crud::PAGE_NEW)

        ]);
        yield MenuItem::section('catégories');
        yield MenuItem::subMenu('Actions', 'fa fa-bars') ->setSubItems([
            MenuItem::linkToCrud("Lister catégorie", 'fas fa-eye',Categorie::class)
                ->setAction(Crud::PAGE_INDEX),
            MenuItem::linkToCrud("Ajouter catégorie", 'fas fa-plus',Categorie::class)
                ->setAction(Crud::PAGE_NEW)
        ]);
        yield MenuItem::section('Contacts');
        yield MenuItem::subMenu('Actions', 'fa fa-bars') ->setSubItems([
            MenuItem::linkToCrud("Lister Contact", 'fas fa-eye',Contact::class)
                ->setAction(Crud::PAGE_INDEX)
        ]);

    }
}
