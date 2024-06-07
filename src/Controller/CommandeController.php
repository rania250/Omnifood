<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $commande->setCreatedAt(new \DateTime());
        $commande->setUpdatedAt(new \DateTime());
        $commande->setStatus('En cours');
        $commande->setUser($this->getUser());

        $entityManager->persist($commande);
        $entityManager->flush();

        return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
    }

    #[Route('/{id}/add-produit', name: 'app_commande_add_produit', methods: ['POST'])]
    public function addProduit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $produitId = $request->request->get('produit_id');
        $quantite = $request->request->get('quantite');
        $produit = $entityManager->getRepository(Produit::class)->find($produitId);

        if ($produit) {
            $produitCommande = new ProduitCommande();
            $produitCommande->setProduit($produit);
            $produitCommande->setQuantite($quantite);
            $produitCommande->setPrix($produit->getPrix() * $quantite); // assuming getPrix returns unit price
            $produitCommande->setCommande($commande);

            $commande->addProduitCommande($produitCommande);
            $commande->setUpdatedAt(new \DateTime());
            $commande->setTotale($commande->calculerPrixTotal());

            $entityManager->persist($produitCommande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $status = $request->request->get('status');
            $remarque = $request->request->get('remarque');

            if ($status) {
                $commande->setStatus($status);
            }
            if ($remarque) {
                $commande->setRemarque($remarque);
            }

            $commande->setUpdatedAt(new \DateTime());

            $entityManager->flush();

            return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['DELETE'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index');
        }

        return $this->json(['error' => 'Jeton CSRF invalide'], Response::HTTP_FORBIDDEN);
    }
}
