<?php

namespace App\Controller;

use App\Entity\Workshop;
use App\Entity\Event;
use App\Form\WorkshopType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/workshop')]
class WorkshopController extends AbstractController
{
    #[Route('/Workshops/{idEvent}', name: 'app_workshop_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Event $event): Response
    {
        $workshops = $entityManager
            ->getRepository(Workshop::class)
            ->findByidEvent($event->getIdEvent());

        return $this->render('workshop/index.html.twig', [
            'workshops' => $workshops,
            'event'=>$event,
        ]);
    }
    #[Route('front_{idEvent}-{id_user}', name: 'app_workshop_index_front', methods: ['GET'])]
    public function indexFront(EntityManagerInterface $entityManager,Event $event,$id_user): Response
    {
        $workshops = $entityManager
            ->getRepository(Workshop::class)
            ->findByidEvent($event->getIdEvent());

        return $this->render('workshop/indexFront.html.twig', [
            'workshops' => $workshops,
            'event'=>$event,
            'id_user'=>$id_user
        ]);
    }
    #[Route('/new/{idEvent}', name: 'app_workshop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Event $event): Response
    {
        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $targetDirectory = $this->getParameter('kernel.project_dir') . '/public';
            $file->move($targetDirectory, $fileName);

           
            $workshop->setImage($fileName);
            $workshop->setIdEvent($event->getIdEvent());
            $entityManager->persist($workshop);
            $entityManager->flush();

            return $this->redirectToRoute('app_workshop_index', ['idEvent'=>$event->getIdEvent()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workshop/new.html.twig', [
            'workshop' => $workshop,
            'form' => $form,
        ]);
    }

    #[Route('/{idWorkshop}', name: 'app_workshop_show', methods: ['GET'])]
    public function show(Workshop $workshop): Response
    {
        return $this->render('workshop/show.html.twig', [
            'workshop' => $workshop,
        ]);
    }

    #[Route('/{idWorkshop}/edit', name: 'app_workshop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Workshop $workshop, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $targetDirectory = $this->getParameter('kernel.project_dir') . '/public';
            $file->move($targetDirectory, $fileName);
            $workshop->setImage($fileName);
            $entityManager->flush();

            return $this->redirectToRoute('app_workshop_index',['idEvent'=>$workshop->getIdEvent()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('workshop/edit.html.twig', [
            'workshop' => $workshop,
            'form' => $form,
        ]);
    }

    #[Route('/{idWorkshop}', name: 'app_workshop_delete', methods: ['POST'])]
    public function delete(Request $request, Workshop $workshop, EntityManagerInterface $entityManager): Response
    {
        $id=$workshop->getIdEvent();
        if ($this->isCsrfTokenValid('delete'.$workshop->getIdWorkshop(), $request->request->get('_token'))) {
            $entityManager->remove($workshop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_workshop_index', ['idEvent'=>$id], Response::HTTP_SEE_OTHER);
    }
}
