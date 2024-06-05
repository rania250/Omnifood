<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            if ($file instanceof UploadedFile) {
                // Gérer le téléchargement du fichier
                $fileName = $this->uploadContenu($file);
                // Mettre à jour le contenu de la recette avec le nom du fichier
                $produit->setImage($fileName);
            }
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            if ($file instanceof UploadedFile) {
                // Gérer le téléchargement du fichier
                $fileName = $this->uploadContenu($file);
                // Mettre à jour le contenu de la recette avec le nom du fichier
                $produit->setImage($fileName);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    private function uploadContenu(UploadedFile $file): string
    {
        $uploadsDirectory = $this->getParameter('uploads_directory');

        // Générer un nom de fichier unique
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        try {
            // Déplacer le fichier vers le répertoire où vous souhaitez le stocker
            $file->move($uploadsDirectory, $fileName);
        } catch (FileException $e) {
            // Gérer les erreurs de téléchargement ici, si nécessaire
            throw new FileException('Une erreur s\'est produite lors de l\'enregistrement du fichier.');
        }

        // Utiliser MimeTypeGuesser pour obtenir le type MIME
        $guesser = MimeTypes::getDefault();
        $mimeType = $guesser->guessMimeType($uploadsDirectory . '/' . $fileName);

        if ($mimeType === null) {
            throw new FileException('Impossible de récupérer le type MIME du fichier.');
        }

        // Vous pouvez faire d'autres traitements ici si nécessaire

        return $fileName;

    }
}
