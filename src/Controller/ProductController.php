<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\AuctionRepository;
use App\Repository\EventRepository;
use App\Repository\ForumRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductController extends AbstractController
{
    private $id_user;

   

    #[Route('/product_{id_user}', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository , Request $req,$id_user): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'id_user' =>$id_user
        ]);
    }

    #[Route('/addprod{id_user}', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $repoUser,$id_user): Response
    {
     
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
            //setting the time
            $today = new DateTime();
            $formattedDate = $today->format('d-m-Y');
            $product->setCreationdate($formattedDate);
            //setting User
            $User = $repoUser->find($id_user);
            $product->setIdUser($User);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('productimage')->getData();
            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                // Move the file to the desired directory
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );
                // Store the file path in the database
                $filePath = 'C:/Users/Hei/OneDrive/Documents/GitHub/Sprint-Web/public/uploads/' . $fileName;
                $product->setProductimage($filePath);
            }
            $entityManager->persist($product);
            $entityManager->flush();
           // return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_product_index',['id_user' => $id_user]);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
            'id_user' => $id_user
        ]);
    }

    #[Route('/show{idProduct}_{id_user}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product,$id_user): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
            'id_user'=>$id_user
        ]);
    }

    #[Route('/edit_{idProduct}_{id_user}_prod', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager,UserRepository $repoUser,$id_user): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
            //setting the time
            $today = new DateTime();
            $formattedDate = $today->format('d-m-Y');
            $product->setCreationdate($formattedDate);
            //setting User
            $User = $repoUser->find(4);
            $product->setIdUser($User);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('productimage')->getData();
            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                // Move the file to the desired directory
                $file->move(
                    $this->getParameter('upload_directory'),
                    $fileName
                );
                // Store the file path in the database
                $filePath = 'C:/Users/Hei/OneDrive/Documents/GitHub/Sprint-Web/public/uploads/' . $fileName;
                $product->setProductimage($filePath);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_product_index', ['id_user'=>$id_user], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
            'id_user'=>$id_user
        ]);
    }

    #[Route('/delete{idProduct}_prod_{id_user}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager,$id_user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getIdProduct(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_index', ['id_user'=>$id_user], Response::HTTP_SEE_OTHER);
    }

    #[Route('/Adminprods', name: 'AdminProds')]
    public function Admin( AuctionRepository $repoAuc,ProductRepository $repo , UserRepository $repoU , ForumRepository $repoF,EventRepository $repoE): Response

    {
        $auctionnumbers = $repoAuc->numberOfAuctions();
        $usernumbers = $repoU ->numberOfUsers();
        $NumForums = $repoF ->numberOfForums();
        $productsnumbers= $repo -> numberOfProducts();
        $prods = $repo->findAll() ; 
        $eventnumbers = $repoE->numberOfEvents();
        return $this->render('admin/ProdsAdmin.html.twig', [
            'prods' => $prods ,
            'usernumber'=> $usernumbers,
            'NumForms'=> $NumForums,
            'productnumber'=> $productsnumbers,
            'NumEvents'=> $eventnumbers,
            'NumAuctions'=> $auctionnumbers,
        ]);
    }
    #[Route('/productforsale{id_user}', name: 'app_product_forsale', methods: ['GET'])]
    public function forsaleprod(ProductRepository $productRepository , $id_user): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findBy(['forsale' => true]),
            'id_user' =>$id_user
            
        ]);
    }
    #[Route('/productnotforsale{id_user}', name: 'app_product_notforsale', methods: ['GET'])]
    public function notforsaleprod(ProductRepository $productRepository, $id_user): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findBy(['forsale' => false]),
            'id_user' =>$id_user
            
        ]);
    }





    #[Route('/deleteadmin{idProduct}', name: 'app_admin_delete')]
    public function deleteadmin(ManagerRegistry $manager,ProductRepository $repo,$idProduct){
        $product = $repo->find($idProduct);
        $manager->getManager()->remove($product);
        $manager->getManager()->flush();
        return $this->redirectToRoute('AdminProds');
    }








    #[Route('/app_search_{id_user}', name: 'app_search', methods: ['GET'])]
    public function search(Request $request,ProductRepository $productRepository,$id_user): Response
    {
        $searchBy = $request->query->get('searchby');
        $searchText = $request->query->get('searchtext');
        // Query the database based on search criteria
         if ($searchBy && $searchText) {
        if ($searchBy === 'title') {
            $products = $productRepository->findByPartialTitle($searchText);
        } elseif ($searchBy === 'description') {
            $products = $productRepository->findByPartialDescription($searchText);
        } else {
            // Handle invalid searchBy value
            $products = [];
        }
        } else {
        // Handle case when searchBy or searchText is not provided
        $products = $productRepository->findAll(); // Or any default logic you want
         }

         

    return $this->render('product/index.html.twig', [
        'products' => $products,
        'id_user'=>$id_user
    ]);
    }
}
