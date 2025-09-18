<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\User;
use App\Repository\CommandeRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends AbstractDashboardController
{
    private CommandeRepository $commandeRepository;

    public function __construct(CommandeRepository $commandeRepository)
    {
        $this->commandeRepository = $commandeRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_login');
        }

        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('access_denied');
        }

        $pendingOrders = $this->commandeRepository->countPendingOrders();
        $validatedOrders = $this->commandeRepository->countValidatedOrders();
        $canceledOrders = $this->commandeRepository->countCanceledOrders();
        $deliveredOrders = $this->commandeRepository->countDeliveredOrders();

        return $this->render('admin/dashboard.html.twig', [
            'pendingOrders' => $pendingOrders,
            'validatedOrders' => $validatedOrders,
            'canceledOrders' => $canceledOrders,
            'deliveredOrders' => $deliveredOrders,
        ]);
    }

    #[Route('/admin/check-new-orders', name: 'admin_check_new_orders')]
    public function checkNewOrders(SessionInterface $session): JsonResponse
    {
        $newOrders = $session->get('new_orders', []);
        $session->remove('new_orders');  // Clear new orders after fetching

        return new JsonResponse($newOrders);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Foodie');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Categorie', 'fas fa-list', Categorie::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Produit::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::section('Gestion des commandes');
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Commande::class);
    }
}
