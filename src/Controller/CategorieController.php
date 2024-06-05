<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            // Vérifier s'il y a un fichier envoyé
            if ($file instanceof UploadedFile) { // Vérifie si le fichier est une instance de UploadedFile
                // Gérer le téléchargement du fichier
                $fileName = $this->uploadContenu($file);

                // Mettre à jour le contenu de la recette avec le nom du fichier
                $categorie->setImage($fileName);
            }
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            // Vérifier s'il y a un fichier envoyé
            if ($file instanceof UploadedFile) { // Vérifie si le fichier est une instance de UploadedFile
                // Gérer le téléchargement du fichier
                $fileName = $this->uploadContenu($file);

                // Mettre à jour le contenu de la recette avec le nom du fichier
                $categorie->setImage($fileName);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
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
