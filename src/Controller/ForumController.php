<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\OpenWeatherMapService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ForumController extends AbstractController
{
    ///////////////////////////////////
    private $weatherService;
    public function __construct(OpenWeatherMapService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    public function getWeather()
    {
        $city = 'Gouvernorat de Tunis'; // Example city
        $weatherData = $this->weatherService->getWeather($city);

        $placeName = $weatherData['name'];
        $latitude = $weatherData['coord']['lat'];
        $longitude = $weatherData['coord']['lon'];
        $currentTempKelvin = $weatherData['main']['temp'];

        $currentTempCelsius = $currentTempKelvin - 273.15;

        $formattedWeatherData = "Place Name: $placeName\n";
        $formattedWeatherData .= "Latitude: $latitude\n";
        $formattedWeatherData .= "Longitude: $longitude\n";
        $formattedWeatherData .= "Current Temperature: $currentTempCelsius °C";

        $Ar =[$placeName,$currentTempCelsius,$latitude,$longitude];

        return $Ar;
    }
    
    ///////////////////////////////////
    #[Route('/forum', name: 'app_forum')]
    public function index(): Response
    {
        return $this->render('forum/index.html.twig', [
            'controller_name' => 'ForumController',
        ]);
    }
    ///LIST WITH PAGINATION/////
    #[Route('/forumlist',name:'app_list_forums')]
    public function getAll(ForumRepository $repo, PaginatorInterface $paginator,Request $req){
        $data = $repo->findAll();
        $forums = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            4
        );
        $Ar = [];
        $Ar[] = $this->getWeather();
        return $this->render('forum/displayForums.html.twig',[
            'forums'=>$forums,
            'weather'=>$Ar,
        ]);
    }
   

    ///////////////////////////  ADDDDDDINNNNNNNNNGGGGGGG ////////////////////////////////////
    //Add a Forum
    #[Route('/forumadd',name:'app_add_forum')]
    public function AddForum(Request $req,ManagerRegistry $manager){
        $Newforum = new Forum();
        $form = $this->createForm(ForumType::class,$Newforum);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $Newforum->setRepliesNumber(0);
            $today = new DateTime();
            $Newforum->setDate($today);
            $manager->getManager()->persist($Newforum);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_forums');
        }
        return $this->render('forum/addForum.html.twig',['f'=>$form->createView()]);
    }
    //Add a Forum keep User ID connected
    #[Route('/forumadds_{idu}',name:'app_add_forum_user')]
    public function AddForumWithUserConnected($idu,Request $req,ManagerRegistry $manager){
        $Newforum = new Forum();
        $user = $idu;
        $form = $this->createForm(ForumType::class,$Newforum);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $Newforum->setRepliesNumber(0);
            $today = new DateTime();
            $Newforum->setDate($today);
            $manager->getManager()->persist($Newforum);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_lists_forum_user', ['idu' => $user]);
        }
        return $this->render('forum/addForum.html.twig',['f'=>$form->createView(),'idu' => $user]);
    }
    /////////////////////////////////////////////////////////////////////////////////////////////

    //Delete the Forums
    #[Route('/forum/delete/{id}',name:'app_delete_forum')]
    public function delete($id,ManagerRegistry $manager,ForumRepository $repo){
        $forum = $repo->find($id);
        $manager->getManager()->remove($forum);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_list_forums');
    }
    //Update the Forum
    #[Route('/forumupdate{id}',name:'app_update_author')]
    public function update(ForumRepository $rep,$id,Request $req,ManagerRegistry $manager){
        $forum = $rep->find($id);
        $form = $this->createForm(ForumType::class,$forum);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            $manager->getManager()->persist($forum);
            $manager->getManager()->flush();
        return $this->redirectToRoute('app_list_forums');
        }
        return $this->render('forum/addForum.html.twig',['f'=>$form->createView()]);
    }
    //////////////   Search SECTION   //////////////
    //List The Forums to search
    #[Route('/forumlistsearchres_{idu}',name:'forums_search_res')]
    public function searchfunc($idu,Request $request, ForumRepository $forumRepository,UserRepository $Urepo) : Response
    {
        $query = $request->query->get('query');
        $forums = $forumRepository->searchByName($query); // Implement this method in your ForumRepository

        if($query != null){
            $forums = $forumRepository->SEARCH($query);
        }else{
            $forums = $forumRepository->findAll();
        }

        //keep user
        $user = $Urepo -> find($idu);

        return $this->render('forum/searchResults.html.twig', [
            'forums' => $forums,
            'idu' => $user,
        ]);
    }
    #[Route('/forumlistsearch_{idu}',name:'forums_search')]
    public function search($idu,ForumRepository $repo, Request $request,UserRepository $Urepo) : Response
    {
        
        $searchTerm = $request->query->get('query');

        // Perform search logic here
        if($searchTerm != null){
            $searchResults = $repo->SEARCH($searchTerm);
        }else{
            $searchResults = $repo->findAll();
        }
        
        //keep user
        $user = $Urepo -> find($idu);
        
        return $this->render('forum/search.html.twig', [
            'forums' => $searchResults,
            'idu' => $user,
        ]);
    }

    //////////////   ADMIN SECTION   //////////////
    #[Route('/adminForums', name: 'ForumsAdmin')]
    public function AdminForums(UserRepository $Urepo,ForumRepository $repo,ProductRepository $repoP): Response
    {
        $forums = $repo->findAll() ; 
        $NumForums = $repo ->numberOfForums();
        $productsnumbers= $repoP -> numberOfProducts();

        $users = $Urepo->findAll() ; 
        $usernumbers = $Urepo ->numberOfUsers();

        return $this->render('admin/ForumsAdmin.html.twig', [
            'forums' => $forums ,
            'NumForms'=> $NumForums,
            'users' => $users ,
            'usernumber'=> $usernumbers,
            'productnumber'=> $productsnumbers
        ]);
    }

    //Delete the Forums Admin
    #[Route('/forum/admin/delete/{id}',name:'AdminDelete')]
    public function Admindelete($id,ManagerRegistry $manager,ForumRepository $repo){
        $forum = $repo->find($id);
        $manager->getManager()->remove($forum);
        $manager->getManager()->flush();
        return $this->redirectToRoute('ForumsAdmin');
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    //                _    _  _____ ______ _____  
    //               | |  | |/ ____|  ____|  __ \ 
    //               | |  | | (___ | |__  | |__) |
    //               | |  | |\___ \|  __| |  _  / 
    //               | |__| |____) | |____| | \ \ 
    //                \____/|_____/|______|_|  \_\
    //
    ///////////////////////////////////////////////////////////////////////////////////////

     ///LIST WITH PAGINATION WITH USER CONNECTED/////
     #[Route('/forumslist_{idu}',name:'app_lists_forum_user')]
     public function getsAll($idu,UserRepository $Urepo,ForumRepository $repo, PaginatorInterface $paginator,Request $req){
         //Pagination Section
         $data = $repo->findAll();
         $forums = $paginator->paginate(
             $data,
             $req->query->getInt('page',1),
             4
         );
         //Weather Section
         $Ar = [];
         $Ar[] = $this->getWeather();
         // User Section//
         $user = $Urepo->find($idu);
         /////////////////
         return $this->render('forum/displayForums.html.twig',[
             'forums'=>$forums,
             'weather'=>$Ar,
             'idu'=>$user,
         ]);
     }
}
