<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Discussion;
use App\Entity\Message;
use App\Entity\User;
use App\Form\DiscussionType;
use App\Form\MessageType;
use App\Repository\UserRepository;
use App\Repository\DiscussionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MessageRepository;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\This;
use App\Form\SignalType;
use App\Repository\AuctionRepository;
use App\Repository\EventRepository;
use App\Repository\ForumRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/discussion')]
class DiscussionController extends AbstractController
{

    private ?int $currentUserId;

    public function SetcurrUser( $iduser)
    {
        $this->currentUserId = (int)$iduser;
    }




#[Route('-{id_user}', name: 'app_discussion_index', methods: ['GET'])]
public function index(DiscussionRepository $discussionRepository, UserRepository $userRepository,$id_user): Response
{

    $this->SetcurrUser($id_user);
    // Récupérer les discussions où l'utilisateur courant est soit l'expéditeur (sender) ou le destinataire (receiver)
    $discussions = $discussionRepository->findDiscussionsByUser($this->currentUserId);

    return $this->render('discussion/afficherDis.html.twig', [
        'discussions' => $discussions,
        'id_user'=>$id_user
    ]);
}




// #[Route('new_{id_user}', name: 'app_discussion_new', methods: ['GET', 'POST'])]
// public function new(Request $request, EntityManagerInterface $entityManager , $id_user): Response
// {
//     $this->SetcurrUser($id_user);
//     $discussion = new Discussion();
//     $form = $this->createForm(DiscussionType::class, $discussion);
//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {

//         //existence de la discussion
//         $receiver = $discussion->getReceiver();
//         if ($receiver !== null) {
//             $receiverId = $receiver->getIdUser();
//             $existingDiscussion = $entityManager->getRepository(Discussion::class)->findExistingDiscussion($this->currentUserId, $receiverId);
//             if ($existingDiscussion) {
//                 // Une discussion existe déjà, rediriger vers cette discussion
//                 return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $existingDiscussion->getIddis()], Response::HTTP_SEE_OTHER);
//             }

//             // Aucune discussion existante, procéder à la création de la nouvelle discussion
//             $discussion->setIdsender($this->currentUserId);
//             $discussion->setSig(null);

//             $entityManager->persist($discussion);
//             $entityManager->flush();

//             return $this->redirectToRoute('app_discussion_show_messages', ['iddis' => $discussion->getIddis()], Response::HTTP_SEE_OTHER);
//         } else {
//             // Si aucun destinataire n'a été sélectionné, afficher un message d'erreur ou rediriger vers une page appropriée
//             // Ici, je redirige vers la page de création de discussion avec un message d'erreur
//             $this->addFlash('error', 'Veuillez sélectionner un destinataire.');
//             return $this->redirectToRoute('app_discussion_new');
//         }
//     }

//     // Le formulaire n'est pas soumis ou n'est pas valide
//     // Afficher le formulaire de création de discussion
//     return $this->renderForm('discussion/new.html.twig', [
//         'discussion' => $discussion,
//         'form' => $form,
//         'id_user'=>$this->currentUserId,
//     ]);
// }

// //Add a Forum
// #[Route('-discadd_{idu}',name:'app_add_ds')]
// public function AddForum($idu,Request $req,ManagerRegistry $manager){
//     $Newforum = new Discussion();
//     $form = $this->createForm(DiscussionType::class,$Newforum);
//     $form->handleRequest($req);
//     if($form->isSubmitted()){
//         $manager->getManager()->persist($Newforum);
//         $manager->getManager()->flush();
//         return $this->redirectToRoute('app_list_forums');
//     }
//     return $this->render('discussion/new.html.twig',[
//         'discussion' => $Newforum
//         ,'form'=>$form->createView(),
//         'id_user'=>$idu,
// ]);
// }

#[Route('new_disc_{id_user}', name: 'app_discussion_new_two')]
public function newDis(Request $request, EntityManagerInterface $entityManager , $id_user): Response
{
    $this->SetcurrUser($id_user);
    $discussion = new Discussion();
    $form = $this->createForm(DiscussionType::class, $discussion);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        //existence de la discussion
        $receiver = $discussion->getReceiver();
        if ($receiver !== null) {
            $receiverId = $receiver->getIdUser();
            $existingDiscussion = $entityManager->getRepository(Discussion::class)->findExistingDiscussion($this->currentUserId, $receiverId);
            if ($existingDiscussion) {
                // Une discussion existe déjà, rediriger vers cette discussion
                return $this->redirectToRoute('app_discussion_show_messages', ['id_user'=>$id_user,'iddis' => $existingDiscussion->getIddis()], Response::HTTP_SEE_OTHER);
            }

            // Aucune discussion existante, procéder à la création de la nouvelle discussion
            $discussion->setIdsender($this->currentUserId);
            $discussion->setSig(null);

            $entityManager->persist($discussion);
            $entityManager->flush();

            return $this->redirectToRoute('app_discussion_show_messages', ['id_user'=>$id_user,'iddis' => $discussion->getIddis()], Response::HTTP_SEE_OTHER);
        } else {
            // Si aucun destinataire n'a été sélectionné, afficher un message d'erreur ou rediriger vers une page appropriée
            // Ici, je redirige vers la page de création de discussion avec un message d'erreur
            $this->addFlash('error', 'Veuillez sélectionner un destinataire.');
            return $this->redirectToRoute('app_discussion_new_two');
        }
    }

    // Le formulaire n'est pas soumis ou n'est pas valide
    // Afficher le formulaire de création de discussion
    return $this->renderForm('discussion/new.html.twig', [
        'discussion' => $discussion,
        'form' => $form,
        'id_user'=>$id_user,
    ]);
}


    #[Route('d-{iddis}_{id_user}', name: 'app_discussion_show', methods: ['GET'])]
    public function show(Discussion $discussion , $id_user): Response
    {
        $this->SetcurrUser($id_user);

        return $this->render('discussion/show.html.twig', [
            'discussion' => $discussion,
            'id_user'=>$id_user,
        ]);
    }
    

    #[Route('-{iddis}_edit_{id_user}', name: 'app_discussion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Discussion $discussion, EntityManagerInterface $entityManager , $id_user): Response
    {
        $this->SetcurrUser($id_user);
        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_discussion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('discussion/edit.html.twig', [
            'discussion' => $discussion,
            'form' => $form,
            'id_user' => $this->currentUserId,
        ]);
    }

    #[Route('deletedis{iddis}_{id_user}', name: 'app_discussion_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Discussion $discussion, EntityManagerInterface $entityManager , $id_user): Response
    {
        $this->SetcurrUser($id_user);
        if ($this->isCsrfTokenValid('delete' . $discussion->getIddis(), $request->request->get('_token'))) {
            $entityManager->remove($discussion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_discussion_index', ['id_user' => $id_user], Response::HTTP_SEE_OTHER);
    }

    #[Route('messages_{iddis}_{id_user}', name: 'app_discussion_show_messages', methods: ['GET', 'POST'])]
    public function showMessages( NotificationController $notificationController ,$iddis, Request $request,MessageRepository $messageRepository,$id_user, EntityManagerInterface $entityManager): Response
    {
        $this->SetcurrUser($id_user);
        $discussion = $this->getDoctrine()->getRepository(Discussion::class)->find($iddis);

       // $idCurrentUser = User.get_current_user();
        $user = null ;
        //$sender = $userRepository->findUserById($idCurrentUser);


        $message = new Message();
        $message->setIdsender($this->currentUserId);
        $message->setIddis($iddis);

        $messages = $messageRepository->findBy(['iddis' => $iddis]);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $notificationController->sendNotification($request);

            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_discussion_show_messages', ['id_user' => $id_user,
                                                                            'iddis'=>$iddis], 
                        Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/messages.html.twig', [
            'discussion' => $discussion,
            'messages' => $messages,
            //'sender' => $sender ,
            'form' => $form->createView(),
            'id_user' => $id_user,

        ]);
    }


    #[Route('signaler-{iddis}-{id_user}', name: 'app_discussion_signal', methods: ['GET', 'POST'])]
    public function signaler(Request $request, Discussion $discussion, EntityManagerInterface $entityManager , $id_user): Response
    {
        $this->SetcurrUser($id_user);
        $form = $this->createForm(SignalType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $discussion->setSig($data['motif']);
            $entityManager->flush();

            return $this->redirectToRoute('app_discussion_show_messages',  ['id_user' => $id_user,
            'iddis' => $discussion->getIddis()]);
        }

        return $this->render('discussion/signaler.html.twig', [
            'form' => $form->createView(),
            'discussion' => $discussion,
            'id_user' => $this->currentUserId,
        ]);
    }


    //Admin

    #[Route('annulersignaler-{iddis}', name: 'app_discussion_annul_signal', methods: ['GET', 'POST'])]
    public function AnuulerSignaler(Request $request,DiscussionRepository $repo , Discussion $discussion, EntityManagerInterface $entityManager ): Response
    {
        $discussion->setSig(null);
        $discussions = $repo->findByNonEmptySig();

        return $this->render('discussion/Admin.html.twig', [
            'discussion' => $discussion,
            'discussions' => $discussions ,
        ]);
    }

    #[Route('discussionlist', name: 'app_list_disc')]
    public function getAll(DiscussionRepository $repo,AuctionRepository $repoAuc,UserRepository $repoU,ForumRepository $repoF,ProductRepository $repoP,EventRepository $repoE): Response
    {
        $NumForums = $repoF ->numberOfForums(); 
        $usernumbers = $repoU ->numberOfUsers();
        $productsnumbers= $repoP -> numberOfProducts();
        $eventnumbers = $repoE->numberOfEvents();
        $auctionnumbers = $repoAuc->numberOfAuctions();
        $discussions = $repo->findByNonEmptySig();
        return $this->render('discussion/Admin.html.twig', [
            'discussions' => $discussions,
            'usernumber'=> $usernumbers,
            'NumForms'=> $NumForums,
            'productnumber'=> $productsnumbers,
            'NumEvents'=> $eventnumbers,
            'NumAuctions'=> $auctionnumbers,
        ]);
    }

    
    

    //Recherche

    #[Route('discussionsearchres_{id_user}',name:'discussions_search_res')]
    public function searchResults(Request $request, discussionRepository $discussionRepository , $id_user) : Response
    {
        $query = $request->query->get('query');
        $this->SetcurrUser($id_user);

        if($query != null){
            $discussions = $discussionRepository->SEARCH($query );
        }else{
            $discussions = $discussionRepository->findAll();
        }

        return $this->render('discussion/discussionResults.html.twig', [
            'discussions' => $discussions,
            'id_user' => $this->currentUserId,
        ]);
    }


    #[Route('discussionlistsearch_{id_user}',name:'discussions_search')]
    public function search(discussionRepository $repo, Request $request , $id_user) : Response
    {
        $searchTerm = $request->query->get('query');
        $this->SetcurrUser($id_user);

        if($searchTerm != null){
            $searchResults = $repo->SEARCH($searchTerm );
        }else{
            $searchResults = $repo->findAll();
        }
        $searchResults = $repo->findAll();

        return $this->render('discussion/search.html.twig', [
            'discussion' => $searchResults,
            'id_user'=>$this->currentUserId,
        ]);
    }




}
