<?php

// src/Controller/ProfileController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile()
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}

