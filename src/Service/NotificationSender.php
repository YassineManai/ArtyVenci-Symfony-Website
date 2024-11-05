<?php

// src/Service/NotificationSender.php
namespace App\Service;

use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;

class NotificationSender
{
    public function sendNotification($receiver, $message)
    {
        $notification = new Notification();
        $notification
            ->setTitle('Nouveau message')
            ->setBody('Vous avez reçu un nouveau message : ' . $message);

        $notifier = NotifierFactory::create();
        $notifier->send($notification);
    }
}
