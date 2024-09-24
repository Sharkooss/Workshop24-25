<?php

// src/Controller/ShopController.php

namespace App\Controller;

use App\Entity\Buy;
use App\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ShopController extends AbstractController
{
    #[Route('/buy/{itemId}', name: 'app_buy_item', methods: ['POST'])]
    public function buyItem(int $itemId, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le produit
        $product = $entityManager->getRepository(Shop::class)->find($itemId);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        // Vérifier si l'utilisateur a assez de points
        if ($user->getPointUsers() < $product->getPriceShop()) {
            $this->addFlash('error', 'Vous n\'avez pas assez de points pour acheter cet item.');
            return $this->redirectToRoute('app_home');
        }

        // Vérifier le stock
        if ($product->getStockShop() <= 0) {
            $this->addFlash('error', 'Ce produit est en rupture de stock.');
            return $this->redirectToRoute('app_home');
        }

        // Débiter les points de l'utilisateur
        $user->setPointUsers($user->getPointUsers() - $product->getPriceShop());

        // Diminuer le stock du produit
        $product->setStockShop($product->getStockShop() - 1);

        // Créer un nouvel achat
        $buy = new Buy();
        $buy->setIdUsers($user);
        $buy->setIsShop($product);

        // Enregistrer les changements
        $entityManager->persist($buy);
        $entityManager->persist($product);
        $entityManager->flush();

        // Ajouter un message de succès
        $this->addFlash('success', 'Achat réussi pour ' . $product->getNameShop());

        return $this->redirectToRoute('app_home');
    }
}
