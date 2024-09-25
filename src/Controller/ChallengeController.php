<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Challenge;
use App\Entity\Challenged;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ChallengeController extends AbstractController
{
    #[Route('/challenge/initiate/{id}', name: 'app_challenge_initiate')]
    public function initiateChallenge(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $challengedUser = $entityManager->getRepository(Users::class)->find($id);

        if (!$challengedUser) {
            throw $this->createNotFoundException('Ami non trouvé');
        }

        if ($request->isMethod('POST')) {
            $amount = (int) $request->request->get('amount');

            if ($amount <= 0 || $amount > $user->getPointUsers() || $amount > $challengedUser->getPointUsers()) {
                $this->addFlash('danger', 'Le montant n\'est pas valide ou dépasse le solde.');
            } else {
                $challenge = new Challenge();
                $challenge->setNameChallenge('Défi entre amis');
                $challenge->setDescritpionChallenge('Un défi lancé entre amis pour ' . $amount . ' UniTs');

                $challenged = new Challenged();
                $challenged->setChallenger($user);  // Définit l'utilisateur comme le challenger
                $challenged->setOpponent($challengedUser); // Définit l'ami comme l'adversaire
                $challenged->setIdChallenge($challenge);
                $challenged->setAmount($amount);

                $entityManager->persist($challenge);
                $entityManager->persist($challenged);
                $entityManager->flush();

                $this->addFlash('success', 'Défi envoyé à ' . $challengedUser->getName());

                return $this->redirectToRoute('app_friends');
            }
        }

        return $this->render('challenge/initiate.html.twig', [
            'friend' => $challengedUser
        ]);
    }

    #[Route('/challenge/accept/{id}', name: 'app_challenge_accept')]
    public function acceptChallenge(int $id, EntityManagerInterface $entityManager): Response
    {
        $challenged = $entityManager->getRepository(Challenged::class)->find($id);
        $user = $this->getUser();

        if ($challenged->getOpponent() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accepter ce défi.');
        }

        $challenged->setStatus('accepted');
        $entityManager->flush();

        $this->addFlash('success', 'Vous avez accepté le défi.');

        return $this->redirectToRoute('app_challenge_show', ['id' => $challenged->getIdChallenge()->getId()]);
    }

    #[Route('/challenge/show/{id}', name: 'app_challenge_show')]
    public function showChallenge(int $id, EntityManagerInterface $entityManager): Response
    {
        $challenge = $entityManager->getRepository(Challenge::class)->find($id);
    
        // Vérifier si la sélection est complète
        $participants = $challenge->getChallengeds();
        $selectionComplete = true;
    
        foreach ($participants as $participant) {
            if ($participant->getSelectedWinnerChallenger() === null || $participant->getSelectedWinnerOpponent() === null) {
                $selectionComplete = false;
                break;
            }
        }
    
        return $this->render('challenge/show.html.twig', [
            'challenge' => $challenge,
            'selectionComplete' => $selectionComplete,
        ]);
    }
    

    #[Route('/challenge/select_winner/{id}', name: 'app_challenge_select_winner', methods: ['POST'])]
    public function selectWinner(int $id, EntityManagerInterface $entityManager): Response
    {
        $challenged = $entityManager->getRepository(Challenged::class)->find($id);
        $user = $this->getUser();
        $challenge = $challenged->getIdChallenge();

        if ($challenged->getChallenger() !== $user && $challenged->getOpponent() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas sélectionner un gagnant pour ce défi.');
        }

        $selectedWinnerId = $user->getId();

        if ($challenged->getChallenger() === $user) {
            $challenged->setSelectedWinnerChallenger($selectedWinnerId);
        } else {
            $challenged->setSelectedWinnerOpponent($selectedWinnerId);
        }

        $participants = $challenge->getChallengeds();
        $winner = null;
        $selectionComplete = true;

        foreach ($participants as $participant) {
            if ($participant->getSelectedWinnerChallenger() === null || $participant->getSelectedWinnerOpponent() === null) {
                $selectionComplete = false;
                break;
            }
            if ($winner === null) {
                $winner = $participant->getSelectedWinnerChallenger();
            } elseif ($winner !== $participant->getSelectedWinnerOpponent()) {
                $selectionComplete = false;
                break;
            }
        }

        if ($selectionComplete && $winner !== null) {
            $this->handleUnitTransfer($winner, $challenge, $entityManager);
            $this->addFlash('success', 'Le défi est terminé et le gagnant a été désigné.');
        } else {
            $this->addFlash('info', 'Votre sélection a été enregistrée. En attente de l\'autre joueur.');
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_challenge_show', ['id' => $challenge->getId()]);
    }

    private function handleUnitTransfer(int $winnerId, Challenge $challenge, EntityManagerInterface $entityManager): void
    {
        $winner = $entityManager->getRepository(Users::class)->find($winnerId);
        $participants = $challenge->getChallengeds();
        $totalAmount = 0;

        foreach ($participants as $participant) {
            $totalAmount += $participant->getAmount();
            if ($participant->getChallenger()->getId() !== $winnerId && $participant->getOpponent()->getId() !== $winnerId) {
                $loser = $participant->getChallenger()->getId() !== $winnerId ? $participant->getChallenger() : $participant->getOpponent();
                $loser->setPointUsers($loser->getPointUsers() - $participant->getAmount());
            }
        }

        $winner->setPointUsers($winner->getPointUsers() + $totalAmount);
        $entityManager->flush();
    }
}