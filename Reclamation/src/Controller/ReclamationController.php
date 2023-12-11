<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(Request $request, ReclamationRepository $reclamationRep): Response
    {
        $reclamation= new Reclamation;

        // create the form
        $form = $this->createForm(ReclamationType::class, $reclamation);
 
        // handle the form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $reclamation->setStatus('NEW');
            $reclamation->setSeverityLevel('HIGH');
           
            // process the form data
            $reclamationRep->save($reclamation, true);

            // redirect to a success page or do something else
            return $this->redirectToRoute('app_reclamation');
        }
        return $this->render('reclamation/reclamation.html.twig', [
            'controller_name' => 'ReclamationController',
            'form' => $form->createView(),
        ]);
    }

}
