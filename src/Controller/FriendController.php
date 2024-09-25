<?php
// src/Controller/FriendController.php

namespace App\Controller;

use App\Entity\Challenged;
use App\Entity\FriendRequest;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FriendController extends AbstractController
{

    #[Route('/friend', name: 'app_friends')]
    public function friends(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
    
        // Récupérer les amis actuels (demandes acceptées)
        $friends = $entityManager->getRepository(FriendRequest::class)->createQueryBuilder('fr')
            ->where('(fr.sender = :user OR fr.receiver = :user) AND fr.status = :accepted')
            ->setParameter('user', $user)
            ->setParameter('accepted', 'accepted')
            ->getQuery()
            ->getResult();
    
        // Récupérer les demandes d'amis reçues et en attente
        $receivedRequests = $entityManager->getRepository(FriendRequest::class)->createQueryBuilder('fr')
            ->where('fr.receiver = :user AND fr.status = :pending')
            ->setParameter('user', $user)
            ->setParameter('pending', 'pending')
            ->getQuery()
            ->getResult();
    
        // Récupérer les défis envoyés par l'utilisateur
        $challengesSent = $entityManager->getRepository(Challenged::class)->findBy([
            'challenger' => $user,
        ]);
    
        // Récupérer les défis reçus par l'utilisateur
        $challengesReceived = $entityManager->getRepository(Challenged::class)->findBy([
            'opponent' => $user,
        ]);
    
        $friendDetails = [];
        foreach ($friends as $friendRequest) {
            $friendDetails[] = $friendRequest->getSender() === $user ? $friendRequest->getReceiver() : $friendRequest->getSender();
        }
    
        return $this->render('friend/index.html.twig', [
            'friends' => $friendDetails,
            'receivedRequests' => $receivedRequests, // Ajout des demandes reçues
            'challengesSent' => $challengesSent,
            'challengesReceived' => $challengesReceived,
        ]);
    }
    
    

    #[Route('/friend/search', name: 'app_friend_search', methods: ['GET'])]
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = $request->query->get('q');
        $user = $this->getUser();
    
        // Récupérer les utilisateurs qui correspondent à la recherche
        $users = $entityManager->getRepository(Users::class)->createQueryBuilder('u')
            ->where('u.name LIKE :query OR u.surname_users LIKE :query')
            ->andWhere('u != :currentUser')
            ->setParameter('query', '%' . $query . '%')
            ->setParameter('currentUser', $user)
            ->getQuery()
            ->getResult();
    
        // Récupérer les amis actuels ou les demandes en cours
        $existingFriends = $entityManager->getRepository(FriendRequest::class)->createQueryBuilder('fr')
            ->where('(fr.sender = :user OR fr.receiver = :user)')
            ->andWhere('fr.status IN (:statuses)')
            ->setParameter('user', $user)
            ->setParameter('statuses', ['accepted', 'pending'])
            ->getQuery()
            ->getResult();
    
        // Créer un tableau des IDs d'amis et de demandes en cours pour exclure
        $excludedIds = [];
        foreach ($existingFriends as $friendRequest) {
            $friend = ($friendRequest->getSender() === $user) ? $friendRequest->getReceiver() : $friendRequest->getSender();
            $excludedIds[] = $friend->getId();
        }
    
        // Filtrer les utilisateurs pour exclure ceux qui sont déjà amis ou avec des demandes en cours
        $filteredUsers = array_filter($users, function ($user) use ($excludedIds) {
            return !in_array($user->getId(), $excludedIds);
        });
    
        return $this->render('friend/search.html.twig', [
            'users' => $filteredUsers,
            'query' => $query,
        ]);
    }
    

    #[Route('/friend/request/{id}', name: 'app_friend_request', methods: ['POST'])]
    public function sendFriendRequest(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
    
        // Récupérer l'utilisateur cible à partir de l'ID
        $receiver = $entityManager->getRepository(Users::class)->find($id);
    
        if (!$receiver) {
            // Si l'utilisateur n'existe pas, afficher une erreur
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
    
        // Vérifier si une demande existe déjà (envoyée ou reçue, en statut pending ou accepted)
        $existingRequest = $entityManager->getRepository(FriendRequest::class)->createQueryBuilder('fr')
            ->where('(fr.sender = :user AND fr.receiver = :receiver) OR (fr.sender = :receiver AND fr.receiver = :user)')
            ->andWhere('fr.status IN (:statuses)')
            ->setParameter('user', $user)
            ->setParameter('receiver', $receiver)
            ->setParameter('statuses', ['pending', 'accepted'])
            ->getQuery()
            ->getOneOrNullResult();
    
        if ($existingRequest) {
            // Si une demande d'ami est déjà en cours ou acceptée, on empêche d'en envoyer une nouvelle
            if ($existingRequest->getStatus() === 'accepted') {
                $this->addFlash('warning', 'Vous êtes déjà amis avec cet utilisateur.');
            } elseif ($existingRequest->getStatus() === 'pending') {
                $this->addFlash('warning', 'Une demande d\'ami est déjà en attente.');
            }
        } else {
            // Créer une nouvelle demande d'ami
            $friendRequest = new FriendRequest();
            $friendRequest->setSender($user);
            $friendRequest->setReceiver($receiver);
            $friendRequest->setStatus('pending');
    
            $entityManager->persist($friendRequest);
            $entityManager->flush();
    
            $this->addFlash('success', 'Demande d\'ami envoyée.');
        }
    
        return $this->redirectToRoute('app_friends');
    }

    #[Route('/friend/request/respond/{id}', name: 'app_friend_respond', methods: ['POST'])]
    public function respondToFriendRequest(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la demande d'ami à partir de l'ID
        $friendRequest = $entityManager->getRepository(FriendRequest::class)->find($id);

        if (!$friendRequest) {
            throw $this->createNotFoundException('Demande d\'ami non trouvée.');
        }

        // Récupérer la réponse envoyée par l'utilisateur
        $response = $request->request->get('response'); // "accept" ou "reject"

        // Mise à jour du statut de la demande d'ami
        if ($response === 'accept') {
            $friendRequest->setStatus('accepted');
            $this->addFlash('success', 'Demande d\'ami acceptée.');
        } elseif ($response === 'reject') {
            $friendRequest->setStatus('rejected');
            $this->addFlash('danger', 'Demande d\'ami rejetée.');
        }

        // Enregistrer les modifications dans la base de données
        $entityManager->persist($friendRequest);
        $entityManager->flush();

        return $this->redirectToRoute('app_friends');
    }

    // src/Controller/FriendController.php

    #[Route('/challenge/{id}', name: 'app_challenge')]
    public function challenge(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $friend = $entityManager->getRepository(Users::class)->find($id);

        if (!$friend) {
            throw $this->createNotFoundException('Ami non trouvé.');
        }

        if ($request->isMethod('POST')) {
            $amount = (int) $request->request->get('amount');

            // Vérifier que les deux utilisateurs ont assez de points pour le défi
            if ($user->getPointUsers() < $amount || $friend->getPointUsers() < $amount) {
                $this->addFlash('danger', 'Vous ou votre ami n\'avez pas assez de UniTs pour ce défi.');
                return $this->redirectToRoute('app_challenge', ['id' => $id]);
            }

            // Créer un nouveau défi
            $challenge = new Challenge();
            $challenge->setNameChallenge("Défi entre " . $user->getName() . " et " . $friend->getName());
            $challenge->setDescritpionChallenge('Un défi amical !');

            // Ajouter les utilisateurs à la table "Challenged"
            $challengedUser = new Challenged();
            $challengedUser->setIdUsers($user);
            $challengedUser->setIdChallenge($challenge);
            $challengedUser->setAmount($amount);

            $challengedFriend = new Challenged();
            $challengedFriend->setIdUsers($friend);
            $challengedFriend->setIdChallenge($challenge);
            $challengedFriend->setAmount($amount);

            $entityManager->persist($challenge);
            $entityManager->persist($challengedUser);
            $entityManager->persist($challengedFriend);
            $entityManager->flush();

            return $this->redirectToRoute('app_challenge_show', ['id' => $challenge->getId()]);
        }

        return $this->render('challenge/new.html.twig', [
            'friend' => $friend,
        ]);
    }

}