<?php

namespace App\Controller\Admin;

use App\Entity\AuditFacture;
use App\Entity\Facture;
use App\EventSubscriber\FactureSubscriber;
use App\Repository\AuditFactureRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;

//#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private $auditRepo;

    public function __construct(AuditFactureRepository $auditRepo)
    {
        $this->auditRepo = $auditRepo;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();
        /*$routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(InscriptionCrudController::class)->generateUrl();
        return $this->redirect($url);*/


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        $executionCounts = $this->auditRepo->findCountActionsByType();

        return $this->render('admin/dashboard.html.twig', [
            'execution_counts' => $executionCounts,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Gestion des factures des clients');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home',);
        //->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Facture', 'fa-solid fa-user ', Facture::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Audit Facture', 'fa-solid fa-user', AuditFacture::class)
            ->setPermission('ROLE_SUPER_ADMIN');
    }
}
