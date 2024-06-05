<?php

namespace App\Controller;

use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\MimeTypes;
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
    public function editProfile(Request $request, UserInterface $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mise à jour des informations de l'utilisateur à partir des données soumises par le formulaire
            $user->setFullName($form->get('fullName')->getData());
            // Pas besoin de mettre à jour la photo ici, car cela est déjà géré dans la méthode uploadContenu

            // Persist the user entity
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('security/edit_profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
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