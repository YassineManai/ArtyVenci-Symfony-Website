<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Workshop;
use App\Entity\User;
use App\Entity\Participation;
use App\Form\EventType;
use App\Repository\AuctionRepository;
use App\Repository\EventRepository;
use App\Repository\ForumRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,UserRepository $repo,ForumRepository $repoF,ProductRepository $repoP,EventRepository $repoE,AuctionRepository $repoAuc): Response
    {
        $events = $entityManager
            ->getRepository(Event::class)
            ->findAll();
            $workshops = $entityManager
            ->getRepository(Workshop::class)
            ->findAll();
            $NumForums = $repoF ->numberOfForums(); 
            $usernumbers = $repo ->numberOfUsers();
            $productsnumbers= $repoP -> numberOfProducts();
            $eventnumbers = $repoE->numberOfEvents();
            $auctionnumbers = $repoAuc->numberOfAuctions();
        return $this->render('event/index.html.twig', [
            'events' => $events,
            'workshops'=>$workshops,
            'usernumber'=> $usernumbers,
            'NumForms'=> $NumForums,
            'productnumber'=> $productsnumbers,
            'NumEvents'=> $eventnumbers,
            'NumAuctions'=> $auctionnumbers,

        ]);
    }
    #[Route('/pdfcatalog', name: 'app_event_pdf', methods: ['GET'])]
    public function PDF(EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager
            ->getRepository(Event::class)
            ->findAll();
    
        // Create a Dompdf instance
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        // Load HTML content for the PDF
        $html = $this->renderView('event/pdf.html.twig', [
            'events' => $events,
        ]);
    
        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);
    
        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
    
        // Render the HTML as PDF
        $dompdf->render();
    
        // Output the generated PDF to the browser
        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="event_catalog.pdf"'
            ]
        );
    }
    #[Route('/search', name: 'app_event_search', methods: ['GET','POST'])]
    public function search(Request $request,EntityManagerInterface $entityManager): Response
    {
        $workshops = $entityManager
        ->getRepository(Workshop::class)
        ->findAll();
        $search=$request->request->get('search');
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('e')
                     ->from(Event::class, 'e')
                     ->where($queryBuilder->expr()->like('e.eName', ':search'))
                     ->setParameter('search', '%' . $search . '%');
        
        $events = $queryBuilder->getQuery()->getResult();
    
        return $this->render('event/index.html.twig', [
            'events' => $events,
            'workshops'=>$workshops,
        ]);
    }
    #[Route('/participations', name: 'app_participation_index', methods: ['GET'])]
    public function participations(EntityManagerInterface $entityManager): Response
    {
        $parts = $entityManager
            ->getRepository(Participation::class)
            ->findAll();

        return $this->render('event/indexP.html.twig', [
            'participations' => $parts,
        ]);
    }
    #[Route('Front{id_user}', name: 'app_event_index_front', methods: ['GET'])]
    public function indexFront(EntityManagerInterface $entityManager,$id_user): Response
    {
        $events = $entityManager
            ->getRepository(Event::class)
            ->findAll();

        return $this->render('event/indexFront.html.twig', [
            'events' => $events,
            'id_user' =>$id_user
        ]);
    }
    #[Route('/participer/{idEvent}_{id_user}', name: 'app_event_participation', methods: ['GET'])]
    public function participer(EntityManagerInterface $entityManager,Event $e,UserRepository $uREPO,$id_user): Response
    {
        $p=new participation();
        $p->setIdEvent($e);
        $repository = $entityManager->getRepository(User::class);
        $user = $uREPO->find($id_user);
        $p->setIdUser($user);
        $entityManager->persist($p);
        $entityManager->flush();
        return $this->redirectToRoute('app_event_index_front', ['id_user'=>$id_user], Response::HTTP_SEE_OTHER);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $targetDirectory = $this->getParameter('kernel.project_dir') . '/public';
            $file->move($targetDirectory, $fileName);

           
            $event->setImage($fileName);
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{idEvent}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $targetDirectory = $this->getParameter('kernel.project_dir') . '/public';
            $file->move($targetDirectory, $fileName);

           
            $event->setImage($fileName);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_event_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getIdEvent(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
    }
}
