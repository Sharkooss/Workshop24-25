<?php
// src/Controller/RankingController.php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RankingController extends AbstractController
{
    #[Route('/ranking', name: 'app_ranking')]
    public function ranking(EntityManagerInterface $entityManager): Response
    {
        // Utiliser le nom correct du champ "point_users"
        $users = $entityManager->getRepository(Users::class)->findBy([], ['point_users' => 'DESC']);

        return $this->render('ranking/index.html.twig', [
            'users' => $users,
        ]);
    }
}
