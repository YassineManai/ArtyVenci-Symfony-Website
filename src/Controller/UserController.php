<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\SignupType;
use App\Repository\AuctionRepository;
use App\Repository\EventRepository;
use App\Repository\ForumRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Twilio\Rest\Client;

class UserController extends AbstractController
{


    
    #[Route('', name: 'login')]
    public function login(Request $req ): Response
    {
        $error = $req->query->get('error');

        return $this->render('user/Login.html.twig', [
            'controller_name' => 'UserController',
            'error' => $error
        ]);
    }

    #[Route('/user/authenticate', name: 'authenticate')]
    public function authenticate(Request $request, UserRepository $repo): Response
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
    
        
        if ($username === null || $username === '') {
          
            return $this->redirectToRoute('login', ['error' => 'Username is required']);
        }
    
       
        $user = $repo->findOneByUsername($username);
    
        if (!$user) {
           
            return $this->redirectToRoute('login', ['error' => 'Couldnt find that username']);
        }
        if ($user->getRole() === "Admin") {
            
            return $this->redirectToRoute('Admin');
        }else{
            if ($user->getPassword() === $password && $user->getStatus() !== "Blocked") {
            
                return $this->redirectToRoute('app_product_index',['id_user' => $user->getIdUser()]);
            } else {
                
                return $this->redirectToRoute('login', ['error' => 'Invalid Username or Password']);
            }
        }
    
      
      
    }


    #[Route('/user/signup', name: 'signup', methods: ['GET', 'POST'])]
    public function Opensignup(): Response
    {
        return $this->render('user/SignUp_Ui/SignUp.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/signup/step2', name: 'signup2' , methods: ['GET', 'POST'])]
    public function Opensignup2(Request $req): Response
    {
        
    $username = $req->query->get('username');
    $email = $req->query->get('email');
    $password = $req->query->get('password');
    $Role = $req->query->get('role');

    
    
        return $this->render('user/SignUp_Ui/SignUpStep2.html.twig', [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'Role' => $Role,
            'controller_name' => 'UserController',
           
        ]

    );
    }
    #[Route('/user/signup/step3', name: 'signup3', methods: ['GET', 'POST'])]
    public function Opensignup3(Request $req ,ManagerRegistry $manager): Response
    {
        $username = $req->query->get('username');
    $email = $req->query->get('email');
    $password = $req->query->get('password');
    $Role = $req->query->get('Role');
    $FirstName = $req->query->get('FirstName');
    $LastName = $req->query->get('LastName');
    $Adress = $req->query->get('adress');
    $Phone = $req->query->get('Phone');
   
  
    $Gender = $req->query->get('Gender');
    $ph = $req->query->get('Phone1');
  

    $user = new User();
    $form = $this->createForm(SignupType::class,$user);
    $form -> handleRequest($req);
    if ($form->isSubmitted())  
    {  

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($Role);
        $user->setFirstname($FirstName);
        $user->setLastname($LastName);
        $user->setAdress($Adress);
        $user->setDob($ph);
        $user->setPhone($Phone);
        $user->setGender($Gender);
        $file = $form->get('imageurl')->getData();
        if ($file) {
            $fileName = uniqid().'.'.$file->guessExtension();
            
            $file->move(
                $this->getParameter('upload_directory'),
                $fileName
            );
            $filePath = 'C:/Users/Hei/OneDrive/Documents/GitHub/Sprint-Web/public/uploads/' . $fileName;
            $user->setImageurl($filePath);
        }

        $entityManager= $manager->getManager(); 
        $entityManager->persist($user);  
        $entityManager->flush();  
         return $this->redirectToRoute('welcome');
    }
    
        return $this->render('user/SignUp_Ui/SignUpStep3.html.twig', [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'Role' => $Role,
            'FirstName' => $FirstName,
            'LastName' => $LastName,
            'Adress' => $Adress,
            'Phone' => $Phone,
            'Gender' => $Gender,
           

            'controller_name' => 'UserController',
            'form'=>$form->createView()

        ]);
    }

    #[Route('/user/welcome', name: 'welcome')]
    public function Welcome(): Response
    {
        return $this->render('user/SignUp_Ui/SignupStep4.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/Home', name: 'home')]
    public function Home(Request $req): Response
    {  
        $id_user = $req->query->get('id_user');
        return $this->render('Home.html.twig', [
            'id_user' => $id_user
        ]);
    }



    #[Route('/Admin', name: 'Admin')]
    public function Admin( UserRepository $repo,ForumRepository $repoF,ProductRepository $repoP,EventRepository $repoE,AuctionRepository $repoAuc): Response
    {
        $users = $repo->findAll() ;
        $NumForums = $repoF ->numberOfForums(); 
        $usernumbers = $repo ->numberOfUsers();
        $productsnumbers= $repoP -> numberOfProducts();
        $eventnumbers = $repoE->numberOfEvents();
        $auctionnumbers = $repoAuc->numberOfAuctions();
        return $this->render('admin/user/UsersAdmin.html.twig', [
            'users' => $users ,
            'usernumber'=> $usernumbers,
            'NumForms'=> $NumForums,
            'productnumber'=> $productsnumbers,
            'NumEvents'=> $eventnumbers,
            'NumAuctions'=> $auctionnumbers,
        ]);
    }
    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function deleteAuthor(ManagerRegistry $manager,UserRepository $repo,$id){
        $user = $repo->find($id);
        $manager->getManager()->remove($user);
        $manager->getManager()->flush();
        return $this->redirectToRoute('Admin');
    }


    #[Route('/Admin/UserDetails/{id}', name: 'userdetail')]
    public function UserDetails(UserRepository $repo ,ManagerRegistry $manager,$id): Response
    {   $usernumbers = $repo ->numberOfUsers();
        $user = $repo->find($id);

        return $this->render('admin/user/DetailsUser.html.twig', [
            'user' => $user,
            'usernumber'=> $usernumbers,
        ]);
    }

    #[Route('/user/update/{id}', name: 'update')]
    public function Userupdate(Request $request, UserRepository $repo,ManagerRegistry $manager,$id): Response
    {
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $phone = $request->request->get('phone');
        $address = $request->request->get('address');
        $role= $request->request->get('role');
        $birth= $request->request->get('birthdate');
        
        $user = $repo->find($id);

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRole($role);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setAdress($address);
        $user->setDob($birth);
        $user->setPhone($phone);
        
       

        $manager->getManager()->flush();
        return $this->redirectToRoute('Admin');

    
      
    }


    #[Route('/RestPassword', name: 'Reset_Password')]
    public function ResetPassword( Request $req,UserRepository $repo): Response
    {
        $error = $req->query->get('error');
        return $this->render('user/Forget_Password/ResetPassword.html.twig', [
            'error' => $error
        ]);
    }

    #[Route('/sendEmail', name: 'send_Email')]
    public function sendEmail( Request $request, UserRepository $repo, MailerInterface $mailer,ManagerRegistry $manager): Response
    {
        $email = $request->request->get('email');
        $user = $repo->findOneByEmail($email);
       


        //verification
      
        if (!$user) {
           
            return $this->redirectToRoute('Reset_Password', ['error' => 'Couldnt find that email']);
        }



         // Generate random password
    $length = 10; // Change the length of the password as needed
    $password = bin2hex(random_bytes($length / 2)); // Convert random bytes to hexadecimal representation


    $user->setPassword($password);

    $manager->getManager()->flush();

        //send email

        $email = (new Email())
        ->from('artyvenci@demomailtrap.com')
        ->to("$email")
      
        ->subject('Récupération de compte ArtyVenci')
        
        ->html("<head>\n" .
        "    <meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\" />\n" .

        "    <meta name=\"description\" content=\"Reset Password Email Template.\">\n" .
        "    <style type=\"text/css\">\n" .
        "        a:hover {text-decoration: underline !important;}\n" .
        "    </style>\n" .
        "</head>\n" .
        "\n" .
        "<body marginheight=\"0\" topmargin=\"0\" marginwidth=\"0\" style=\"margin: 0px; background-color: #f2f3f8;\" leftmargin=\"0\">\n" .
        "    <!--100% body table-->\n" .
        "    <table cellspacing=\"0\" border=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#f2f3f8\"\n" .
        "        style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;\">\n" .
        "        <tr>\n" .
        "            <td>\n" .
        "                <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"100%\" border=\"0\"\n" .
        "                    align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n" .
        "                    <tr>\n" .
        "                        <td style=\"height:80px;\">&nbsp;</td>\n" .
        "                    </tr>\n" .
        "                    <tr>\n" .
        "                        <td style=\"text-align:center;\">\n" .
        "                          <a href=\"https://rakeshmandal.com\" title=\"logo\" target=\"_blank\">\n" .
        "                            <img width=\"150\" src=\"https://i.ibb.co/q1tznwp/422753690-204703712734911-8704086809119957789-n.png\" title=\"logo\" alt=\"logo\">\n" .
        "                          </a>\n" .
        "                        </td>\n" .
        "                    </tr>\n" .
        "                    <tr>\n" .
        "                        <td style=\"height:20px;\">&nbsp;</td>\n" .
        "                    </tr>\n" .
        "                    <tr>\n" .
        "                        <td>\n" .
        "                            <table width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\"\n" .
        "                                style=\"max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);\">\n" .
        "                                <tr>\n" .
        "                                    <td style=\"height:40px;\">&nbsp;</td>\n" .
        "                                </tr>\n" .
        "                                <tr>\n" .
        "                                    <td style=\"padding:0 35px;\">\n" .
        "                                        <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;\">\n" .
        "                                           Vous avez demandé de récupérer votre compte</h1>\n" .
        "                                        <span\n" .
        "                                            style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n" .
        "                                        <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n" .
        "                                            Voici les informations de récupération de votre compte :\n\n" .
        "                                            Mot de passe : " . $password . "\n\n" .
        "Si vous n'avez pas demandé cette récupération ou si vous avez des préoccupations, veuillez contacter immédiatement notre équipe d'assistance.\n\n" .
        "                                        </p>\n" .

        "                                    </td>\n" .
        "                                </tr>\n" .
        "                                <tr>\n" .
        "                                    <td style=\"height:40px;\">&nbsp;</td>\n" .
        "                                </tr>\n" .
        "                            </table>\n" .
        "                        </td>\n" .
        "                    <tr>\n" .
        "                        <td style=\"height:20px;\">&nbsp;</td>\n" .
        "                    </tr>\n" .
        "                    <tr>\n" .
        "                        <td style=\"text-align:center;\">\n" .
        "                            <p style=\"font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;\">&copy; <strong>www.artyVenci.com</strong></p>\n" .
        "                        </td>\n" .
        "                    </tr>\n" .
        "                    <tr>\n" .
        "                        <td style=\"height:80px;\">&nbsp;</td>\n" .
        "                    </tr>\n" .
        "                </table>\n" .
        "            </td>\n" .
        "        </tr>\n" .
        "    </table>\n" .
        "    <!--/100% body table-->\n" .
        "</body>\n");

    $mailer->send($email);
       
    return $this->redirectToRoute('Reset_Password', []);
           
     
    }


    #[Route('/RestPasswordViaPhone', name: 'Reset_PaswordViaPhone')]
    public function ResetPasswordviaPhone( Request $req,UserRepository $repo): Response
    {
        $error = $req->query->get('error');
        return $this->render('user/Forget_Password/ResetPasswordbyNumber.html.twig', [
            'error' => $error
        ]);
    }












    #[Route('/sendSMS', name: 'send_SMS')]
    public function sendSMS( Request $request, UserRepository $repo,ManagerRegistry $manager): Response
    {
        $phone = $request->request->get('phone');
        $user = $repo->findOneByNumber($phone);
       


        //verification
      
        if (!$user) {
           
            return $this->redirectToRoute('Reset_PaswordViaPhone', ['error' => 'Couldnt find that phone number']);
        }


          // Generate random password
         $length = 10; // Change the length of the password as needed
         $password = bin2hex(random_bytes($length / 2)); // Convert random bytes to hexadecimal representation
     
     
         $user->setPassword($password);
     
         $manager->getManager()->flush();
     
     
               // Initialize Twilio client
            $sid = '';
            $token = '';
            $twilio = new Client($sid, $token);
     
     
            // Send SMS notification
            $recipientPhoneNumber = '+21654189162';
            $message = "Bonjour,\n\n" .
            "Voici les informations de récupération de votre compte :\n\n" .
            "Nom d'utilisateur : " . $user->getFirstname(). "\n" .
            "Mot de passe : " . $password . "\n\n" .
            "Si vous n'avez pas demandé cette récupération ou si vous avez des préoccupations, veuillez contacter immédiatement notre équipe d'assistance.\n\n" .
            "Merci,\n" .
            "ArtyVenci";
     
     
              
            $twilio->messages->create(
             $recipientPhoneNumber,
             [
                 'from' => '', // Your Twilio phone number
                 'body' => $message,
        ]
    );

    return $this->redirectToRoute('Reset_Password', []);
           
     
    }


    #[Route('/user/block/{id}', name: 'user_block')]
    public function blockUser(ManagerRegistry $manager,UserRepository $repo,$id){
        $user = $repo->find($id);
        $user->setStatus('Blocked');
        $manager->getManager()->flush();
        return $this->redirectToRoute('Admin');
    }

    #[Route('/BlockUsers', name: 'Block_Users')]
    public function BlockedUsers(UserRepository $repo): Response
    {
        $users = $repo->findBy(['status' => 'Blocked']);
        $usernumbers = $repo->numberOfUsers();
        return $this->render('admin/user/BlockedUser.html.twig', [
            'users' => $users,
            'usernumber' => $usernumbers,
        ]);
    }
    #[Route('/user/unblock/{id}', name: 'user_Unblock')]
    public function UnblockUser(ManagerRegistry $manager,UserRepository $repo,$id){
        $user = $repo->find($id);
        $user->setStatus('Unblock');
        $manager->getManager()->flush();
        return $this->redirectToRoute('Admin');
    }




   #[Route('/profile{id_user}', name: 'Profile')]
    public function Profile(Request $req,UserRepository $repo,$id_user): Response
    {
        $user = $repo->find($id_user);
        return $this->render('user/Profile.html.twig', [
           'user'=>$user
        ]);
    }

    #[Route('/profileupdate/{id}', name: 'update')]
    public function profileUpdate(Request $request, UserRepository $repo,ManagerRegistry $manager,$id): Response
    {
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $phone = $request->request->get('phone');
        $address = $request->request->get('address');
        $role= $request->request->get('role');
        $birth= $request->request->get('birthdate');
        
        $user = $repo->find($id);

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRole($role);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setAdress($address);
        $user->setDob($birth);
        $user->setPhone($phone);
        
       

        $manager->getManager()->flush();
        return $this->redirectToRoute('Admin');

    
      
    }
    #[Route('/search/{username}', name: 'search')]
    public function search( UserRepository $repo,$username): Response
    {
        $usernumbers = $repo->numberOfUsers();

        $users = $repo->findBy(['firstname'=> $username ], ['firstname' => 'ASC']);
        return $this->render('/admin/user/SearchUser.html.twig',['users'=>$users, 'usernumber' => $usernumbers,]);
       
    }
    #[Route('/profileUp/{id}', name: 'update_profile')]
    public function profileUp(Request $request, UserRepository $repo,ManagerRegistry $manager,$id): Response
    {
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        $username = $request->request->get('username');
       
        $phone = $request->request->get('phone');
        $address = $request->request->get('address');
       
        $birth= $request->request->get('birthdate');
        
        $user = $repo->find($id);

        $user->setUsername($username);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setAdress($address);
        $user->setDob($birth);
        $user->setPhone($phone);
        
       

        $manager->getManager()->flush();
        return $this->redirectToRoute('Profile', [
            'user'=>$user,
            'id_user'=>$id
         ]);

    
      
    }


    #[Route('/ResetPassword{id_user}', name: 'changePassword')]
    public function ChangePassword(Request $request, UserRepository $repo,ManagerRegistry $manager,$id_user): Response
    {
        $error = $request->query->get('error');
        return $this->render('user/changepassword.html.twig',['id_user'=>$id_user, 'error' => $error]);
        
    
      
    }

    #[Route('/updatePassword/{id_user}', name: 'upassword')]
    public function updatepass(Request $request, UserRepository $repo,ManagerRegistry $manager,$id_user): Response
    {
        $oldpass = $request->request->get('oldp');
        $newpass = $request->request->get('newp');

        
        $user = $repo->find($id_user);

        if ($oldpass === null || $newpass === '') {
          
            return $this->redirectToRoute('changePassword', ['error' => 'field is Empty','id_user'=>$id_user]);
        }

        if ($user->getPassword() == $oldpass)
        {
            $user->setPassword($newpass);
            $manager->getManager()->flush();
            return $this->redirectToRoute('Profile', ['id_user'=>$id_user]);
            
        }
        else
        {
            return $this->redirectToRoute('changePassword', ['error' => 'Wrong Password','id_user'=>$id_user]);
        }
        
    
    
      
    }
  
    
   

}
