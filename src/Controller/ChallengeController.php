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
    
        // Vérifier que l'utilisateur est bien l'adversaire
        if ($challenged->getOpponent() !== $user && $challenged->getChallenger() !== $user) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accepter ce défi.');
        }
    
        // Mettre à jour le statut pour les deux participants
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
    public function selectWinner(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $challenged = $entityManager->getRepository(Challenged::class)->find($id);
        $user = $this->getUser();
        $challenge = $challenged->getIdChallenge();
    
        // Récupérer les données du gagnant
        $data = json_decode($request->getContent(), true);
        $selectedWinnerId = $data['winner'];
    
        // Vérifier si l'utilisateur fait partie du défi
        if ($challenged->getChallenger()->getId() !== $user->getId() && $challenged->getOpponent()->getId() !== $user->getId()) {
            return $this->json(['error' => 'Vous ne pouvez pas sélectionner un gagnant pour ce défi.'], 403);
        }
    
        // Enregistrer la sélection du gagnant
        if ($challenged->getChallenger()->getId() === $user->getId()) {
            $challenged->setSelectedWinnerChallenger($selectedWinnerId);
        } else {
            $challenged->setSelectedWinnerOpponent($selectedWinnerId);
        }
    
        $entityManager->flush(); // Enregistrer la sélection
    
        // Vérifier si les deux participants ont sélectionné le même gagnant
        if ($challenged->getSelectedWinnerChallenger() !== null && $challenged->getSelectedWinnerOpponent() !== null) {
            if ($challenged->getSelectedWinnerChallenger() === $challenged->getSelectedWinnerOpponent()) {
                $this->handleUnitTransfer($challenged->getSelectedWinnerChallenger(), $challenge, $entityManager);
                $challenged->setStatus('done'); // Met à jour le statut du challenge à "done"
                $entityManager->flush(); // Sauvegarder l'état final
                return $this->json(['success' => true]);
            } else {
                // Réinitialiser les votes si les gagnants ne correspondent pas
                $challenged->setSelectedWinnerChallenger(null);
                $challenged->setSelectedWinnerOpponent(null);
                $entityManager->flush();
                return $this->json(['reset' => true]);
            }
        }
    
        return $this->json(['success' => false]);
    }

    private function handleUnitTransfer(int $winnerId, Challenge $challenge, EntityManagerInterface $entityManager): void
    {
        $winner = $entityManager->getRepository(Users::class)->find($winnerId);
        $participants = $challenge->getChallengeds();
        $totalAmount = 0;
    
        foreach ($participants as $participant) {
            $totalAmount += $participant->getAmount();
            if ($participant->getChallenger()->getId() === $winnerId) {
                $loser = $participant->getOpponent();
            } else {
                $loser = $participant->getChallenger();
            }
    
            $loser->setPointUsers($loser->getPointUsers() - $participant->getAmount());
        }
    
        $winner->setPointUsers($winner->getPointUsers() + $totalAmount);
    
        $entityManager->flush();
    }

    #[Route('/challenge/statuses', name: 'app_challenge_statuses', methods: ['GET'])]
    public function getChallengeStatuses(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
    
        $challengesSent = $entityManager->getRepository(Challenged::class)->findBy(['challenger' => $user]);
        $challengesReceived = $entityManager->getRepository(Challenged::class)->findBy(['opponent' => $user]);
    
        $challengesData = [];
        foreach ($challengesSent as $challenge) {
            $challengesData['sent'][] = [
                'id' => $challenge->getId(),
                'opponent' => $challenge->getOpponent()->getName(),
                'status' => $challenge->getStatus(),
                'amount' => $challenge->getAmount(),
            ];
        }
        foreach ($challengesReceived as $challenge) {
            $challengesData['received'][] = [
                'id' => $challenge->getId(),
                'challenger' => $challenge->getChallenger()->getName(),
                'status' => $challenge->getStatus(),
                'amount' => $challenge->getAmount(),
            ];
        }
    
        return $this->json($challengesData);
    }

}