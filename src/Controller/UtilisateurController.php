<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;
use App\Form\UpdateImageType;
use App\Form\UpdateType;

use Symfony\Component\HttpFoundation\JsonResponse;
class UtilisateurController extends AbstractController
{
    public function __construct(private LoggerInterface $logger)
    {
    }
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/futilisateur', name: 'app_utilisateurF')]
    public function indexF(): Response
    {
        return $this->render('frontbase.html.twig');
    }

    #[Route('/add_utilisateur', name: 'add_utilisateur')]
    public function addUser(ManagerRegistry $registry, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user)
            ->add('Register_Account', SubmitType::class,[
                'attr'=> [
                    'class'=>'form-submit'
            ]]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($user && $user->getMotdepasse()) {
                $this->logger->info( $user->getMotdepasse());
                $password = $passwordHasher->hashPassword($user, $user->getMotdepasse());
                $user->setMotdepasse($password);
                $this->logger->info( $user->getMotdepasse());
            }
    //On recupère le nom de l'image
            $image = $form->get('image')->getData();
            if ($image) {
                //On modifie son nom en y ajoutant la date et on la stocke dans une nouvelle variable
                $image_name = time() . '_' . $image->getClientOriginalName();
                //On le sauvegarde dans le dossier ImgLoad
                $image->move($this->getParameter('image_directory'), $image_name);
                $user->setImage($image_name);
            }
            else
                $user->setImage("gilles est bete");

            //$user->setRoles(['ROLE_PATIENT']);
            $em = $registry->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('utilisateur/front/add.html.twig', [
            'form' => $form->createView(),
            'errors' => 'Email déjà utilisé',
        ]);
    }


    #[Route('/up_utilisateur{cin}', name: 'up_utilisateur')]
    public function alterUser(ManagerRegistry $registry,$cin, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
   $user = $registry->getRepository(Utilisateur::class)->find($cin);
        $form = $this->createForm(UtilisateurType::class, $user)
            ->add('Update_Account', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($user && $user->getMotdepasse()) {
                $password = $passwordHasher->hashPassword($user, $user->getMotdepasse());
                $user->setMotdepasse($password);
            }

            $image = $form->get('image')->getData();
            if ($image) {
                $image_name = time() . '_' . $image->getClientOriginalName();
                $image->move($this->getParameter('image_directory'), $image_name);
                $user->setImage($image_name);
            }

            //$user->setRoles(['ROLE_PATIENT']);
            $em = $registry->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('list_utilisateur');
        }

        return $this->renderForm('utilisateur/front/edit.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/l_utilisateur', name: 'list_utilisateur')]
    public function listeU(UtilisateurRepository $repository,Request $request): Response
    {
        $user=$repository->findAll();
        $query = $request->request->get('query');
        if ($query){
            $user = $repository->findBy(['nom' => $query]);

        }

        return $this->render('utilisateur/back/list.html.twig',[
            'user'=>$user,
        ]);
    }

    #[Route('/s_utilisateur{cin}', name: 'show_utilisateur')]
    public function ShowU(UtilisateurRepository $repository,Utilisateur $user): Response
    {
        return $this->render('utilisateur/back/show.html.twig',[
            'us'=>$user
        ]);
    }

    #[Route('/del_utilisateur{cin}', name: 'del_utilisateur')]
    public function dropU(ManagerRegistry $registry,$cin): Response
    {
       $user = $registry->getRepository(Utilisateur::class)->find($cin);
       $em = $registry->getManager();
       $em->remove($user);
       $em->flush();
       return $this->redirectToRoute('app_utilisateurF');
    }

    #[Route('/user/profil', name: 'profil')]
    public function profil(Request $request, Security $security,UserPasswordHasherInterface $passwordHasher): Response
    {
            $user = $security->getUser();
            $form = $this->createForm(UpdateType::class,$user);
                
                $form->handleRequest($request);
                $entityManager = $this->getDoctrine()->getManager();
        
                // Get the profile repository
                // Find the profile by ID

                if ($form->isSubmitted() && $form->isValid()) {
                    $updateU = new Utilisateur();
                    $formData = $form->getData();
                    $password = $passwordHasher->hashPassword($formData, $formData->getMotdepasse());
                    $formData->setMotdepasse($password);
        
                    $entityManager->persist($formData);
                    $entityManager->flush();
                }

                $formImage = $this->createForm(UpdateImageType::class);
                $formImage->handleRequest($request);
                if ($formImage->isSubmitted() && $formImage->isValid()) {
                    $imageFile = $formImage->get('imageFile')->getData();
                    if ($imageFile) {
                        $uniqueId = uniqid(); // Generate a unique id
                        $newFilename =$uniqueId . '.' . $imageFile->guessExtension(); 
                        dump($newFilename);
                        try {
                            $imageFile->move(
                                '../public/imgLoad',
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // handle exception if something happens during file upload
                        }
            
                        $user->setImage($newFilename);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();
                }
              }
              
    
                return $this->render('utilisateur/front/profil.html.twig', [
                    'controller_name' => 'UserController',
                    'user'=> $user,
                    'form' => $form->createView(),
                    'formImage' => $formImage->createView(),
                ]);
        
    }
    #[Route('/searchu', name: 'ajax_search_ut', methods: ['GET'])]
public function search(Request $request, UtilisateurRepository $utilisateurRepository)
{
    $query = $request->query->get('q');

    $utilisateurs = $utilisateurRepository->findByNom($query);

    $results = [];
    foreach ($utilisateurs as $utilisateur) {
        $results[] = [
            'CIN' => $utilisateur->getCin(),
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'email' => $utilisateur->getEmail(),
            'motdepasse' => $utilisateur->getMotdepasse(),
            'numerotelephone' => $utilisateur->getNumerotelephone(),
            'typeutilisateur' => $utilisateur->getTypeutilisateur(),
            'image' => $utilisateur->getImage(),
        ];
    }

    return $this->json($results);
}

}