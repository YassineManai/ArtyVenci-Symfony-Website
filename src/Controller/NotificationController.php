<?php

// src/Controller/NotificationController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;

class NotificationController extends AbstractController
{
    /**
     * @Route("/send-notification", name="send_notification")
     */
    public function sendNotification(Request $request): Response
    {
        // Votre logique de traitement du formulaire (par exemple, validation des données) peut être ajoutée ici

        // Création d'une nouvelle notification
        $notification = new Notification();
        $notification
            ->setTitle('Nouveau message')
            ->setBody('Vous avez un nouveau message dans votre boîte de réception.');

        // Création d'un notificateur
        $notifier = NotifierFactory::create();

        // Envoi de la notification
        $notifier->send($notification);

        // Redirection vers une autre page après envoi de la notification (facultatif)
        return $this->redirectToRoute('notification_sent');
    }

    /**
     * @Route("/notification-sent", name="notification_sent")
     */
    public function notificationSent(): Response
    {
        return $this->render('notification/sent.html.twig');
    }
}

