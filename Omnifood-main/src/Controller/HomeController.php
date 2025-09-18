<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
