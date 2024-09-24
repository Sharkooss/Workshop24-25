<?php

// src/Controller/HomeController.php

namespace App\Controller;

use App\Repository\ShopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ShopRepository $shopRepository): Response
    {
        // Récupérer tous les produits avec un stock positif
        $products = $shopRepository->findProductsInStock();

        // Récupérer l'utilisateur connecté (si existant)
        $user = $this->getUser();
        $points = $user ? $user->getPointUsers() : 0;

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'points' => $points,
        ]);
    }
}
