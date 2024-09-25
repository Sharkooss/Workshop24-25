<?php

// src/Controller/ChatController.php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat', name: 'app_chat')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $messages = $entityManager->getRepository(Message::class)->findBy([], ['createdAt' => 'ASC']);

        return $this->render('chat/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/chat/send', name: 'app_chat_send', methods: ['POST'])]
    public function sendMessage(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $content = $request->request->get('message');

        if (!$content || !$user) {
            return new Response('Invalid input', 400);
        }

        $message = new Message();
        $message->setSender($user);
        $message->setContent($content);

        $entityManager->persist($message);
        $entityManager->flush();

        return new Response('Message sent', 200);
    }

    #[Route('/chat/messages', name: 'app_chat_messages', methods: ['GET'])]
    public function getMessages(EntityManagerInterface $entityManager): Response
    {
        $messages = $entityManager->getRepository(Message::class)->findBy([], ['createdAt' => 'ASC']);

        $messageData = [];
        foreach ($messages as $message) {
            $messageData[] = [
                'sender' => $message->getSender()->getName(),
                'content' => $message->getContent(),
                'createdAt' => $message->getCreatedAt()->format('H:i'),
                'units' => $message->getSender()->getPointUsers()
            ];
        }

        return $this->json($messageData);
    }
}
