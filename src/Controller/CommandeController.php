<?php

namespace App\Controller;

use App\Entity\Commande;
use DateTime;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Security;


#[Route('/commande')]
class CommandeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        // Utilisation de la méthode findAllOrderByCreatedAt() pour afficher les commandes par ordre de création
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAllOrderByCreatedAt(),
        ]);
    }


    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();

        // Ajoutez la date et l'heure actuelles au champ created_at
        $commande->setCreatedAt(new DateTime());

        // Ajoutez également la date et l'heure actuelles au champ updated_at
        $commande->setUpdatedAt(new DateTime());
        $commande->setUser($this->security->getUser()); // Associer la commande à l'utilisateur connecté

        // Suppression des champs created_at et updated_at du formulaire car ils seront gérés automatiquement
        $form = $this->createForm(CommandeType::class, $commande, ['exclude_fields' => ['created_at', 'updated_at']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
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
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Capturez la date et l'heure actuelles avant de flush les changements
            $commande->setUpdatedAt(new DateTime());

            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdateCommande(Commande $commande, PreUpdateEventArgs $event): void
    {
        // Si le champ updated_at est modifié, mettez à jour sa valeur
        if ($event->hasChangedField('updated_at')) {
            $commande->setUpdatedAt(new DateTime());
        }
    }
}
