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


class UtilisateurController extends AbstractController
{
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
                $password = $passwordHasher->hashPassword($user, $user->getMotdepasse());
                $user->setMotdepasse($password);
            }

            $image = $form->get('image')->getData();
            if ($image) {
                $image_name = time() . '_' . $image->getClientOriginalName();
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
    public function listeU(UtilisateurRepository $repository): Response
    {
        return $this->render('utilisateur/back/list.html.twig',[
            'user'=>$repository->findAll()
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
       return $this->redirectToRoute('list_utilisateur');
    }

}
