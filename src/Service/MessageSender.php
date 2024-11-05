<?php

namespace App\Service;

class MessageSender
{
    private $notificationSender;

    public function __construct(NotificationSender $notificationSender)
    {
        $this->notificationSender = $notificationSender;
    }

    public function sendMessage($receiver, $message)
    {
        // Logique pour envoyer le message

        // Après avoir envoyé le message avec succès, envoyez une notification au destinataire
        $this->notificationSender->sendNotification($receiver, $message);
    }
}
