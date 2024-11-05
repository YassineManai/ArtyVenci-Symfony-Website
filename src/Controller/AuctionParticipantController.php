<?php

namespace App\Controller;

use App\Entity\AuctionParticipant;
use App\Form\AuctionParticipantType;
use App\Repository\AuctionParticipantRepository;
use App\Repository\AuctionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auction/participant')]
class AuctionParticipantController extends AbstractController
{
    #[Route('/', name: 'app_auction_participant_index', methods: ['GET'])]
    public function index(AuctionParticipantRepository $auctionParticipantRepository): Response
    {
        return $this->render('auction_participant/index.html.twig', [
            'auction_participants' => $auctionParticipantRepository->findAll(),
        ]);
    }

    #[Route('_new', name: 'app_auction_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request,AuctionParticipantRepository $APrepo, EntityManagerInterface $entityManager,UserRepository $Urepo,AuctionRepository $Arepo): Response
    {
        if($auctionParticipant = $APrepo->getKenzon(2,4) != null)
        {
            $auctionParticipant = $APrepo->getKenzon(2,4);
        }else{
            $auctionParticipant = new AuctionParticipant();
        }
        $form = $this->createForm(AuctionParticipantType::class, $auctionParticipant);
        $form->handleRequest($request);

        $auctionParticipant->setIdAuction($Arepo->find(2));
        $auctionParticipant->setIdParticipant($Urepo->find(4));

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auctionParticipant);
            $entityManager->flush();
            

            return $this->redirectToRoute('app_auction_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auction_participant/new.html.twig', [
            'auction_participant' => $auctionParticipant,
            'form' => $form,
        ]);
    }

    #[Route('_{idParticipant}', name: 'app_auction_participant_show', methods: ['GET'])]
    public function show(AuctionParticipant $auctionParticipant): Response
    {
        return $this->render('auction_participant/show.html.twig', [
            'auction_participant' => $auctionParticipant,
        ]);
    }

    ////////////////TESTING///////////////

    //List The Posts
    #[Route('/{idP}',name:'app_list_posts')]
    public function getAll($idP,AuctionParticipantRepository $repo){
        $auctions = $repo->find($idP);
        return $this->render('auction_participant/show.html.twig',[
            'posts'=>$auctions
        ]);
    }

    //////////////////////

    #[Route('_{idParticipant}_edit', name: 'app_auction_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AuctionParticipant $auctionParticipant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuctionParticipantType::class, $auctionParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_auction_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('auction_participant/edit.html.twig', [
            'auction_participant' => $auctionParticipant,
            'form' => $form,
        ]);
    }

    #[Route('_{idParticipant}', name: 'app_auction_participant_delete', methods: ['POST'])]
    public function delete(Request $request, AuctionParticipant $auctionParticipant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auctionParticipant->getIdParticipant(), $request->request->get('_token'))) {
            $entityManager->remove($auctionParticipant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_auction_participant_index', [], Response::HTTP_SEE_OTHER);
    }

    
}

