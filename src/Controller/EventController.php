<?php
// src/Controller/EventController.php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participate;
use App\Form\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EventController extends AbstractController
{
    #[Route('/events', name: 'app_events')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les événements
        $events = $entityManager->getRepository(Event::class)->findAll();
        $user = $this->getUser();

        // Récupérer les participations de l'utilisateur actuel
        $participations = $entityManager->getRepository(Participate::class)->findBy(['id_users' => $user]);

        // Construire une liste des événements auxquels l'utilisateur est déjà inscrit
        $participatedEvents = [];
        foreach ($participations as $participation) {
            $participatedEvents[] = $participation->getIdEvent()->getId();
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'participatedEvents' => $participatedEvents,
            'isAdmin' => $user && $user->getRoleUsers() === 'ADMIN', // Vérification si l'utilisateur est un ADMIN
        ]);
    }

    #[Route('/event/manage/{id}', name: 'app_event_manage')]
    public function manageEvent(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Vérification du rôle ADMIN
        if ($user->getRoleUsers() !== 'ADMIN') {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page.');
        }

        // Récupérer l'événement par son id
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'a pas été trouvé.');
        }

        // Récupérer les participants à cet événement
        $participants = $entityManager->getRepository(Participate::class)->findBy(['id_event' => $event]);

        return $this->render('event/manage.html.twig', [
            'event' => $event,
            'participants' => $participants,
        ]);
    }

    #[Route('/event/distribute/{id}', name: 'app_event_distribute', methods: ['POST'])]
    public function distributePrizes(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);
    
        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'a pas été trouvé.');
        }
    
        $data = $request->request->all();
    
        if (!isset($data['rewards']) || !is_array($data['rewards'])) {
            $this->addFlash('danger', 'Aucun participant trouvé pour la distribution des prix.');
            return $this->redirectToRoute('app_event_manage', ['id' => $event->getId()]);
        }
    
        foreach ($data['rewards'] as $participantId => $rewardAmount) {
            $participation = $entityManager->getRepository(Participate::class)->find($participantId);
            if ($participation) {
                $rewardAmount = (int)$rewardAmount;
                $ranking = (int)($data['rankings'][$participantId] ?? null);
                
                $participation->setUnitsAmount($rewardAmount);
                $participation->setRanking($ranking);
                $entityManager->persist($participation);
    
                $user = $participation->getIdUsers();
                if ($rewardAmount > 0) {
                    $user->setPointUsers($user->getPointUsers() + $rewardAmount);
                    $entityManager->persist($user);
                }
            }
        }
    
        $entityManager->flush();
    
        $this->addFlash('success', 'Les récompenses ont été distribuées avec succès.');
        return $this->redirectToRoute('app_event_manage', ['id' => $event->getId()]);
    }

    #[Route('/event/new', name: 'app_event_new')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        // Vérification du rôle ADMIN personnalisé
        if ($user->getRoleUsers() !== 'ADMIN') {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour créer un événement.');
        }

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setIdUsers($this->getUser()); // L'admin qui crée l'événement
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_events');
        }

        return $this->render('event/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/event/join/{id}', name: 'app_event_join')]
    public function joinEvent(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
    
        // Récupérer l'événement par son id
        $event = $entityManager->getRepository(Event::class)->find($id);
    
        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'a pas été trouvé.');
        }
    
        // Vérifier si l'événement est "open"
        if ($event->getStatus() !== 'open') {
            $this->addFlash('danger', 'Les inscriptions à cet événement sont fermées.');
            return $this->redirectToRoute('app_events');
        }
    
        // Vérifier si l'utilisateur est déjà inscrit à cet événement
        $participation = $entityManager->getRepository(Participate::class)
            ->findOneBy(['id_users' => $user, 'id_event' => $event]);
    
        if ($participation) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à cet événement.');
        } else {
            // Inscrire l'utilisateur à l'événement
            $participate = new Participate();
            $participate->setIdUsers($user);
            $participate->setIdEvent($event);
            $entityManager->persist($participate);
            $entityManager->flush();
    
            $this->addFlash('success', 'Inscription réussie à l\'événement.');
        }
    
        return $this->redirectToRoute('app_events');
    }

    #[Route('/event/update-status/{id}', name: 'app_event_update_status', methods: ['POST'])]
    public function updateStatus(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            return $this->json(['success' => false, 'message' => 'Événement non trouvé.'], 404);
        }

        $status = $request->request->get('status');

        if (!in_array($status, ['open', 'close'])) {
            return $this->json(['success' => false, 'message' => 'Statut non valide.'], 400);
        }

        $event->setStatus($status);
        $entityManager->flush();

        return $this->json(['success' => true, 'message' => 'Statut mis à jour.', 'status' => $status]);
    }

    #[Route('/event/participants/{id}', name: 'app_event_participants')]
    public function viewParticipants(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'événement par son id
        $event = $entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            throw $this->createNotFoundException('L\'événement n\'a pas été trouvé.');
        }

        // Récupérer les participants inscrits à cet événement
        $participants = $event->getParticipates();

        return $this->render('event/participants.html.twig', [
            'event' => $event,
            'participants' => $participants,
        ]);
    }

}
