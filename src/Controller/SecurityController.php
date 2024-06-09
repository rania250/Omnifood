<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/profile', name: 'app_profile')]
    public function profile(UserInterface $user): Response
    {
        return $this->render('security/profil.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: '/profile/edit', name: 'app_edit_profile')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur est connecté et s'il s'agit d'une instance de la classe User
        if (!$user instanceof User) {
            throw new \LogicException('User must be logged in and an instance of User class.');
        }


        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $file = $form->get('photo_profil')->getData();

            if ($file instanceof UploadedFile) {
                $fileName = $this->uploadContenu($file);
                $user->setPhotoProfil($fileName);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('security/edit_profile.html.twig', [
            'form' => $form,
        ]);
    }


    private function uploadContenu(UploadedFile $file): string
    {
        $uploadsDirectory = $this->getParameter('uploads_directory');

        // Generate a unique file name
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        try {
            // Move the file to the directory where you want to store it
            $file->move($uploadsDirectory, $fileName);
        } catch (FileException $e) {
            // Handle upload errors here, if needed
            throw new FileException('Une erreur s\'est produite lors de l\'enregistrement du fichier.');
        }

        return $fileName;
    }
}