<?php
// src/Controller/ProfileController.php

namespace App\Controller;

use App\Entity\Buy; // Ajouter l'import de la classe Buy
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les achats de l'utilisateur
        $buys = $entityManager->getRepository(Buy::class)->findBy(['id_users' => $user]);

        return $this->render('profile/index.html.twig', [
            'buys' => $buys,
        ]);
    }
}

