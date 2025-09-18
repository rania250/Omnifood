<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'panier_index')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $panier = $session->get('panier', []);

        $data = [];
        $total = 0;
        $totalQuantity = 0;

        foreach ($panier as $id => $quantite) {
            $produit = $produitRepository->find($id);

            if ($produit) {
                $data[] = [
                    'produit' => $produit,
                    'quantite' => $quantite
                ];
                $total += $produit->getPrix() * $quantite;
                $totalQuantity += $quantite;
            }
        }

        return $this->render('panier/index.html.twig', [
            'data' => $data,
            'total' => $total / 100,
            'totalQuantity' => $totalQuantity // Passer la quantité totale au template
        ]);
    }


    #[Route('/add/{id}', name: 'panier_add')]
    public function add(Produit $produit, SessionInterface $session): Response
    {
        $id = $produit->getId();
        $panier = $session->get('panier', []);

        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('panier_index');
    }

    #[Route('/remove/{id}', name: 'panier_remove')]
    public function remove(Produit $produit, SessionInterface $session): Response
    {
        $id = $produit->getId();
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('panier_index');
    }

    #[Route('/delete/{id}', name: 'panier_delete')]
    public function delete(Produit $produit, SessionInterface $session): Response
    {
        $id = $produit->getId();
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('panier_index');
    }

    #[Route('/vide', name: 'panier_vide')]
    public function empty(SessionInterface $session): Response
    {
        $session->remove('panier');

        return $this->redirectToRoute('panier_index');
    }
}
