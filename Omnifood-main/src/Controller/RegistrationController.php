<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setDateInscription(new DateTime());
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $imageFile = $form->get('photo_profil')->getData();
            if ($imageFile) {
                $newFilename = $this->uploadImage($imageFile);
                $user->setPhotoProfil($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
    private function uploadImage(UploadedFile $file): string
    {
        $imagesDirectory = $this->getParameter('uploads_directory');

        // Générer un nom de fichier unique
        $newFilename = md5(uniqid()) . '.' . $file->guessExtension();

        try {
            // Déplacer le fichier vers le répertoire où vous souhaitez le stocker
            $file->move($imagesDirectory, $newFilename);
        } catch (FileException $e) {
            // Gérer les erreurs de téléchargement ici, si nécessaire
            throw new FileException('Une erreur s\'est produite lors de l\'enregistrement de l\'image.');
        }

        return $newFilename;
    }
}
