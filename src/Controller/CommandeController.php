<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/ajout', name: 'commande_add', methods: ['GET', 'POST'])]
    public function new(SessionInterface $session, Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $currentUser = $this->getUser();
        $panier = $session->get('panier', []);

        if ($panier === []) {
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('app_home');
        }

        $remarque = $request->request->get('remarque');

        $commande = new Commande();
        $commande->setCreatedAt(new \DateTime());
        $commande->setUpdatedAt(new \DateTime());
        $commande->setTotale(0.0);
        $commande->setStatus('en_attente');
        $commande->setRemarque($remarque);
        $commande->setUser($currentUser);

        // Parcourir les éléments du panier et les ajouter à la commande
        foreach ($panier as $id => $quantite) {
            $produit = $produitRepository->find($id);

            if ($produit) {
                $produitCommande = new ProduitCommande();
                $produitCommande->setQuantite($quantite);
                $produitCommande->setPrix($produit->getPrix());
                $produitCommande->setProduit($produit);
                $produitCommande->setCommande($commande);

                $commande->addProduitCommande($produitCommande);
            }
        }

        // Calculer le total de la commande
        $commande->setTotale($commande->calculerPrixTotal());

        // Enregistrer la commande en base de données
        $entityManager->persist($commande);
        $entityManager->flush();

        $newOrders = $session->get('new_orders', []);
        $newOrders[] = 'Une nouvelle commande a été créée.';
        $session->set('new_orders', $newOrders);
        // Vider le panier
        $session->remove('panier');

        // Rediriger vers la page de la commande ou une autre page appropriée
        $this->addFlash('message', 'Commande créée avec succès');
        return $this->redirectToRoute('commande_details', ['id' => $commande->getId()]);
    }
    #[Route('/{id}', name: 'commande_details', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/details.html.twig', [
            'commande' => $commande,
        ]);
    }

}
