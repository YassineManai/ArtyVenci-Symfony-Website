<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Postlikes;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\ForumRepository;
use App\Repository\PostlikesRepository;
use App\Repository\PostRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
    //List The Posts
    #[Route('/postlist',name:'app_list_posts')]
    public function getAll(PostRepository $repo, PaginatorInterface $paginator,Request $req){
        $data = $repo->findAll();
        $posts = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            5
        );
        return $this->render('post/displayPosts.html.twig',[
            'posts'=>$posts
        ]);
    }
    //List The Posts by Their Respective Forums
    #[Route('/postlist_{idf}',name:'app_list_posts_by_forum')]
    public function getpostsbyidforum($idf,PostRepository $repo, PaginatorInterface $paginator,Request $req){
        $data = $repo->getPostsByForumNormalSQL($idf);
        $posts = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            5
        );
        return $this->render('post/displayPosts.html.twig',[
            'posts'=>$posts
        ]);
    }
    //Add a Post
    #[Route('/postadd',name:'app_add_post')]
    public function AddPost(Request $req,ManagerRegistry $manager,UserRepository $repoUser,ForumRepository $repoForum){
        $NewPost = new Post();
        $form = $this->createForm(PostType::class,$NewPost);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            //setting Forum
            $Forum = $repoForum->find(1);
            $NewPost->setIdForum($Forum);
            //setting User
            $User = $repoUser->find(1);
            $NewPost->setIdUser($User);
            //setting the time
            $today = new DateTime();
            $NewPost->setTimeofcreation($today);
            //setting the Likes
            $NewPost->setLikeNumber(0);
            $NewPost->getIdForum()->setRepliesNumber($NewPost->getIdForum()->getRepliesNumber()+1);
            //Saving The Post
            $manager->getManager()->persist($NewPost);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_posts');
        }
        return $this->render('post/addPost.html.twig',['f'=>$form->createView()]);
    }
    //Add a Post witth Different Forum
    #[Route('/postadd_{idf}',name:'app_add_post_diff_forum')]
    public function AddPostForum($idf,Request $req,ManagerRegistry $manager,UserRepository $repoUser,ForumRepository $repoForum){
        $NewPost = new Post();
        $form = $this->createForm(PostType::class,$NewPost);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            //setting Forum
            $Forum = $repoForum->find($idf);
            $NewPost->setIdForum($Forum);
            //setting User
            $User = $repoUser->find(4);
            $NewPost->setIdUser($User);
            //setting the time
            $today = new DateTime();
            $NewPost->setTimeofcreation($today);
            //setting the Likes
            $NewPost->setLikeNumber(0);
            $NewPost->getIdForum()->setRepliesNumber($NewPost->getIdForum()->getRepliesNumber()+1);
            //Saving The Post
            $manager->getManager()->persist($NewPost);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_posts');
        }
        return $this->render('post/addPost.html.twig',['f'=>$form->createView()]);
    }
   
    //Update Post
    #[Route('/postupdate{id}',name:'app_update_post')]
    public function update(PostRepository $rep,$id,Request $req,ManagerRegistry $manager){
        $post = $rep->find($id);
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($req);
        $forumid = $post->getIdForum()->getIdForum();
        if($form->isSubmitted()){
            $manager->getManager()->persist($post);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_posts_by_forum', [
                'idf' => $forumid,
            ]);
        }
        return $this->render('post/addPost.html.twig',['f'=>$form->createView()]);
    }
    //Delete the Forums
    #[Route('/postdelete_{id}',name:'app_delete_post')]
    public function delete($id,ManagerRegistry $manager,PostRepository $repo,ForumRepository $Frepo){
        $post = $repo->find($id);
        $manager->getManager()->remove($post);
        $manager->getManager()->flush();
        $forumid = $post->getIdForum()->getIdForum();
        //Remove 1 Post from Froum Attribute
        $forum = $Frepo->find($forumid);
        $forum->setRepliesNumber($forum->getRepliesNumber()-1);
        $manager->getManager()->persist($forum);
        $manager->getManager()->flush();

        return $this->redirectToRoute('app_list_posts_by_forum', [
            'idf' => $forumid,
        ]);
    }
    //////////////   Works   ////////////////////

    //Update Post Likes
    #[Route('/postLike{id}',name:'app_update_post_like')]
    public function updateLikes(PostRepository $rep,$id,ManagerRegistry $manager,PostlikesRepository $PLrepo){
        $post = $rep->find($id);
        
        $forumid = $post->getIdForum()->getIdForum();
        $postlike = $PLrepo->getPostsLikesByPostQueryBuilder($post->getIdPost(),1);
        if($postlike != null)
        {
            if($postlike->getLikePost() == 1)
            {
                $postlike->setLikePost(0);
                $post->setLikeNumber($post->getLikeNumber()-1);
            }else{
                $postlike->setLikePost(1);
                $post->setLikeNumber($post->getLikeNumber()+1);
            }
        }else{
            $postlike = new Postlikes();
            $postlike->setUser(1);
            $postlike->setPost($post);
            $postlike->setLikePost(1);
            $post->setLikeNumber($post->getLikeNumber()+1);
            $manager->getManager()->persist($postlike);
            $manager->getManager()->flush();
        }
        $manager->getManager()->persist($post);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_list_posts_by_forum', [
            'idf' => $forumid,
        ]);
    }

    

    ///////////////////////////////////////////////

    //////////////   TESTING  ////////////////////

    #[Route('/postslist_sorted_{idf}',name:'app_list_posts_by_forum_sorted')]
    public function getpostsbyidforumSorted($idf,PostRepository $repo, PaginatorInterface $paginator,Request $req){
        $data = $repo->SortByLikesNormalSQL($idf);
        $posts = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            5
        );
        return $this->render('post/displayPosts.html.twig',[
            'posts'=>$posts
        ]);
    }
    
    ///////////////////////////////////////////////

    //////////////   ADMIN SECTION   //////////////
    #[Route('/adminPosts_{idf}', name: 'PostsAdmin')]
    public function AdminPosts($idf,UserRepository $Urepo,PostRepository $Prepo,ForumRepository $Frepo,ProductRepository $repoP): Response
    {
        $posts = $Prepo->getPostsByForumNormalSQL($idf);
        $ForumName = $Frepo->find($idf)->getTitle();
        $NumForums = $Frepo ->numberOfForums();
        $productsnumbers= $repoP -> numberOfProducts();

        $users = $Urepo->findAll() ; 
        $usernumbers = $Urepo ->numberOfUsers();

        return $this->render('admin/PostsAdmin.html.twig', [
            'posts' => $posts ,
            'NumForms'=> $NumForums,
            'NameForum'=>$ForumName,
            'users' => $users ,
            'usernumber'=> $usernumbers,
            'productnumber'=> $productsnumbers,
        ]);
    }


    ///////////////// BUNDLEPDF //////////////////////////

    #[Route('/exportPdf/{id}_{idu}', name: 'app_pdf', methods: ['GET', 'POST'])]
    public function ExportPdf($id,PostRepository $repo,$idu,UserRepository $Urepo) :Response
    {
        
        $posts = $repo->getPostsByForumNormalSQL($id);

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $user =$Urepo->find($idu);
        $html = $this->renderView('post/pdf.html.twig', [
            
            'posts'=>$posts,
            'idu'=>$user,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    //                _    _  _____ ______ _____  
    //               | |  | |/ ____|  ____|  __ \ 
    //               | |  | | (___ | |__  | |__) |
    //               | |  | |\___ \|  __| |  _  / 
    //               | |__| |____) | |____| | \ \ 
    //                \____/|_____/|______|_|  \_\
    //
    ///////////////////////////////////////////////////////////////////////////////////////

    //List The Posts by Their Respective Forums & Connected User
    #[Route('/postlists_{idf}_{idu}',name:'app_list_posts_by_forum_user')]
    public function getpostsbyidforumAndUser($idu,$idf,PostRepository $repo,UserRepository $Urepo , PaginatorInterface $paginator,Request $req){
        $User = $Urepo->find($idu);
        $forumid = $idf;
        $data = $repo->getPostsByForumNormalSQL($idf);
        $posts = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            5
        );
        return $this->render('post/displayPosts.html.twig',[
            'posts'=>$posts,
            'idf'=>$forumid,
            'idu'=>$User
        ]);
    }

    //Add a Post witth Different User and Forum
    #[Route('/postadds{idu}_{idf}',name:'app_add_post_diff_user&forum')]
    public function AddPost2($idu,$idf,Request $req,ManagerRegistry $manager,UserRepository $repoUser,ForumRepository $repoForum){
        //keep the user
        $user = $idu;
        //keep normal function
        $NewPost = new Post();
        $form = $this->createForm(PostType::class,$NewPost);
        $form->handleRequest($req);
        if($form->isSubmitted()){
            //setting Forum
            $Forum = $repoForum->find($idf);
            $NewPost->setIdForum($Forum);
            //setting User
            $User = $repoUser->find($idu);
            $NewPost->setIdUser($User);
            //setting the time
            $today = new DateTime();
            $NewPost->setTimeofcreation($today);
            //setting the Likes
            $NewPost->setLikeNumber(0);
            $NewPost->getIdForum()->setRepliesNumber($NewPost->getIdForum()->getRepliesNumber()+1);
            //Saving The Post
            $manager->getManager()->persist($NewPost);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_posts_by_forum_user', [
                'idf' => $idf,
                'idu' => $user,
            ]);
        }
        return $this->render('post/addPost.html.twig',['f'=>$form->createView(),'idu' => $user]);
    }
    
    //Update Post and Keep Connected USER
    #[Route('/postsupdate{idp}_{idu}',name:'app_update_post_user')]
    public function updateWithConnectedUser(PostRepository $rep,$idp,$idu,Request $req,ManagerRegistry $manager){
        //keep the user
        $user = $idu; 
        //normal Update
        $post = $rep->find($idp);
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($req);
        $forumid = $post->getIdForum()->getIdForum();
        if($form->isSubmitted()){
            $manager->getManager()->persist($post);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_list_posts_by_forum_user', [
                'idf' => $forumid,
                'idu' => $user,
            ]);
        }
        return $this->render('post/addPost.html.twig',['f'=>$form->createView(),'idu' => $user
    ]);
    }

    //Delete the Forums and KEEP USER CONNECTED
    #[Route('/postsdelete_{idp}_{idu}',name:'app_delete_post_user')]
    public function deleteKeepConnectedUSER($idp,$idu,ManagerRegistry $manager,PostRepository $repo,ForumRepository $Frepo){
        //Keep user connected
        $user = $idu;
        //normal delete
        $post = $repo->find($idp);
        $manager->getManager()->remove($post);
        $manager->getManager()->flush();
        $forumid = $post->getIdForum()->getIdForum();
        //Remove 1 Post from Froum Attribute
        $forum = $Frepo->find($forumid);
        $forum->setRepliesNumber($forum->getRepliesNumber()-1);
        $manager->getManager()->persist($forum);
        $manager->getManager()->flush();

        return $this->redirectToRoute('app_list_posts_by_forum_user', [
            'idf' => $forumid,
            'idu' => $user,
        ]);
    }

    //Update Post Likes WITH CONNECTED USER
    #[Route('/postsLike_{idp}_{idu}',name:'app_update_post_like_user')]
    public function updateLikesUSER(PostRepository $rep,$idp,$idu,ManagerRegistry $manager,PostlikesRepository $PLrepo){
        $post = $rep->find($idp);
        $user = $idu;
        $forumid = $post->getIdForum()->getIdForum();
        $postlike = $PLrepo->getPostsLikesByPostQueryBuilder($post->getIdPost(),$idu);
        if($postlike != null)
        {
            if($postlike->getLikePost() == 1)
            {
                $postlike->setLikePost(0);
                $post->setLikeNumber($post->getLikeNumber()-1);
            }else{
                $postlike->setLikePost(1);
                $post->setLikeNumber($post->getLikeNumber()+1);
            }
        }else{
            $postlike = new Postlikes();
            $postlike->setUser($idu);
            $postlike->setPost($post);
            $postlike->setLikePost(1);
            $post->setLikeNumber($post->getLikeNumber()+1);
            $manager->getManager()->persist($postlike);
            $manager->getManager()->flush();
        }
        $manager->getManager()->persist($post);
        $manager->getManager()->flush();
        return $this->redirectToRoute('app_list_posts_by_forum_user', [
            'idf' => $forumid,
            'idu' => $user,
        ]);
    }

     
}
