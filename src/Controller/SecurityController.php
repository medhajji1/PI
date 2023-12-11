<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Utilisateur;
use Twilio\Rest\Client;
use App\Form\ForgetPassType;
use App\Form\ForgetPass2Type;
use App\Form\ForgetPass3Type;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
class SecurityController extends AbstractController
{

    public function __construct(private LoggerInterface $logger)
    {
    }
    
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
        {
            $this->logger->info('SecurityController!!!!!!!');
            // If the user is logged in, redirect them to their profile page.
            if ($this->getUser()) {
                $this->logger->info('SecurityControlle2');
                return $this->redirectToRoute('l_utilisateur');
            }
            $this->logger->info('SecurityControlle3');
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $this->logger->info('SecurityController4');
            $lastUsername = $authenticationUtils->getLastUsername();
            $this->logger->info('SecurityController5');
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
    //public function login(AuthenticationUtils $authenticationUtils,Request $request,SessionInterface $session): Response
    //{

/*
        $user = new Utilisateur();
        $form = $this->createFormBuilder($user)

            ->add(
                'email',
                TextType::class,
                array(
                    'attr' =>
                    array('class' => 'form-control'), 'label' => "email"
                )
            )
            ->add(
                'password',
                TextType::class,
                array(
                    'attr' =>
                    array('class' => 'form-control'), 'label' => "password"
                )
            )



            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


        }*/

        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
       // $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
       /* $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,            
              ]);
    }*/
    #[Route('/user/verifCodeEmail', name: 'verifCodeEmail')]
    public function verifCodeEmail(Request $request,SessionInterface $session): Response
    {
        $errorEmail="";
        $form2 = $this->createForm(ForgetPassType::class);
        $form2->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form2->isSubmitted() && $form2->isValid()) {
            $formData = $form2->getData();
            $user = $this->getDoctrine()
                ->getRepository(Utilisateur::class)
                ->findOneBy(['email' => $formData['email']]);
        
            if ($user instanceof Utilisateur) {
                $session->set('idForVerif', $user->getCin());
                $code = random_int(10000, 99999);
                $session->set('codeVerif', $code);
                $sid = 'AC64bf73b1c5c9b9a16a0c9a119448ad10';
                $token = 'fa80aff5e615af3210d0caad6fb55b9a';
                $twilioPhoneNumber = '+19783156441';
                $recipientPhoneNumber = '+21627406906';
        
                $client = new Client($sid, $token);
                $client->messages->create(
                    $recipientPhoneNumber,
                    [
                        'from' => $twilioPhoneNumber,
                        'body' => "Bonjour verifier votre code \n\n\n\n".$code
                    ]
                );
                

                return $this->redirectToRoute('verifCode');
            } else {
                $errorEmail="email n'existe pas !";
            }   
        }
        return $this->render('security/verifCodeEmail.html.twig', [
            'form2' => $form2->createView(),
            'errorEmail'=>$errorEmail
        ]);
    }
    #[Route('/user/verifCode', name: 'verifCode')]
    public function verifCode(Request $request,SessionInterface $session): Response
    {
        $form3 = $this->createForm(ForgetPass2Type::class);
        $form3->handleRequest($request);
        if ($form3->isSubmitted() && $form3->isValid()) {
            $formData = $form3->getData();
            if ($formData['num']==$session->get('codeVerif')/*"12345"*/ ){
                return $this->redirectToRoute('forgetPass');
            }else{
                $this->addFlash('error', 'code invalide');
            }
            
        }

        return $this->render('security/verifCode.html.twig', [
            'form3' => $form3->createView(),

        ]);
    }


    #[Route('/user/forgetPass', name: 'forgetPass')]
    public function forgetPass(Request $request,SessionInterface $session,UserPasswordHasherInterface $passwordHasher): Response
    {
     
        $userId = $session->get('idForVerif');
        $entityManager = $this->getDoctrine()->getManager();
        $ProfilsRepository= $entityManager->getRepository(Utilisateur::class);
        $profil = $ProfilsRepository->findOneBy(['cin' => $userId]);
        $formTest = $this->createForm(ForgetPass3Type::class);
        $formTest->handleRequest($request);

        if ($formTest->isSubmitted() && $formTest->isValid()) {
            $formData = $formTest->getData();
            if ( $formData['mdpNouveau'] == $formData['mdpConfirmer'] ){
                $user=$profil;
                $plaintextPassword = $formData['mdpNouveau'];
           
                $password = $passwordHasher->hashPassword($user, $plaintextPassword);

                $user->setMotdepasse($password);
                dump($password);
                dump($user);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_login');
            }else{
                $this->addFlash('error', 'les mots de passes ne correpondent pas');
            }
       
        }

        return $this->render('security/forgotPass.html.twig', [
            'controller_name' => 'UserController',
            'formTest' => $formTest->createView(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
