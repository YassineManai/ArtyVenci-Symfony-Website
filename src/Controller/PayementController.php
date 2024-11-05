<?php

namespace App\Controller;

use App\Entity\Orderproduct;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twilio\Rest\Client;


class PayementController extends AbstractController
{
    #[Route('/payement', name: 'app_payement')]
    public function index(): Response
    {
        return $this->render('payement/index.html.twig', [
            'controller_name' => 'PayementController',
        ]);
    }
    #[Route('/checkout{idcheck}', name: 'app_order_checkout')]
    public function checkout(EntityManagerInterface $entityManager, $idcheck): Response
    {
        $product = $entityManager->getRepository(Orderproduct::class)->find($idcheck);
        $price=$product->getPrice();
        $title=$product->getTitle();
        $image_url = 'https://i.imgur.com/vceNLz2.jpeg';
        $stripeSK = "";
        Stripe::setApiKey($stripeSK);
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $title,
                            'images' => [$image_url],
                        ],
                        'unit_amount'  => $price*100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('success_url', ['idcheck' => $idcheck, 'title' => $title, 'price' => $price], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),

        ]);

        return $this->redirect($session->url, 303);
    }
    #[Route('/success-url{title}{price}', name: 'success_url')]
    public function successUrl(EntityManagerInterface $entityManager, $title, $price): Response
    {
       // Initialize Twilio client
       $sid = '';
       $token = '';
       $twilio = new Client($sid, $token);

       // Send SMS notification
       $recipientPhoneNumber = '+21629082229';
       $message = 'Payment successful!
       You will be notified soon when the delivery company gets your order of ' . $title . ' for $' . $price . '.
       Thank you for supporting your artist,
       ArtyVenci';
       
       $twilio->messages->create(
           $recipientPhoneNumber,
           [
               'from' => '+15099564507', // Your Twilio phone number
               'body' => $message,
           ]
       );

        return $this->render('payement\success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payement\fail.html.twig', []);
    }
}
