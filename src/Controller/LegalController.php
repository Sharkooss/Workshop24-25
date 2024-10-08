<?php

// src/Controller/LegalController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/terms', name: 'app_terms')]
    public function terms(): Response
    {
        return $this->render('legal/terms.html.twig');
    }
    #[Route('/politic', name: 'app_politic')]
    public function politic(): Response
    {
        return $this->render('legal/politic.html.twig');
    }
}
