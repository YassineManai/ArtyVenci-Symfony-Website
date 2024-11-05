<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\DiscussionRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\NotificationSender;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use App\Service\MessageSender;


#[Route('/message')]
class MessageController extends AbstractController
{
    
    private ?int $currentUserId;

    public function SetcurrUser( $iduser)
    {
        $this->currentUserId = (int)$iduser;
    }


    #[Route('__{id_user}', name: 'app_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository ,$id_user): Response
    {
        $this->SetcurrUser($id_user);

        return $this->render('message/afficherMsg.html.twig', [
            'messages' => $messageRepository->findAll(),
            'id_user'=>$id_user ,
        ]);
    }



    
    #[Route('new_{id_user}', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, $id_user ,EntityManagerInterface $entityManager , DiscussionRepository $discussionRepository): Response
    {
        $this->SetcurrUser($id_user);

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        $discussion=$discussionRepository->findDiscussionById($message->getIddis());
        $iddis = $message->getIddis();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $iddis , 
            'id_user'=>$id_user], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
            'id_user'=>$id_user ,
        ]);
    }

    #[Route('_{idmsg}_{id_user}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message ,$id_user): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
            'id_user'=>$id_user,
        ]);
    }

    #[Route('{idmsg}_edit_{id_user}', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, $id_user , EntityManagerInterface $entityManager ): Response
    {
        $this->SetcurrUser($id_user);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
            'id_user'=>$id_user ,
        ]);
    }

    #[Route('_{idmsg}_{id_user}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager , $id_user): Response
    {
        $this->SetcurrUser($id_user);

        if ($this->isCsrfTokenValid('delete'.$message->getIdmsg(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        $iddis = $message->getIddis();

        return $this->redirectToRoute('app_discussion_show_messages', 
        ['iddis' => $iddis , 
        'id_user'=>$id_user], Response::HTTP_SEE_OTHER);
    }
}
