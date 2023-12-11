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
        $form = $this->createForm(ReclamationType::class, $reclamation);
        //Request tmathel il requet ili 9a3da tit5dem
        //La méthode handleRequest()du formulaire ta3ml traitement ta3 les donnes ta3 BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setStatus('NEW');
            $reclamation->setSeverityLevel('HIGH');        
            //ReclamationRepository t'interecti m3a BDD bil ORM
            $reclamationRep->save($reclamation, true);
            //save ta3ml save lil donnees li bich titsajel fil BDD
            return $this->redirectToRoute('app_reclamation_list', ['id' => $reclamation->getId()]);

        }
        return $this->render('reclamation/reclamation.html.twig', [
            'controller_name' => 'ReclamationController',
            'form' => $form->createView(),
        ]);
    }
    #[Route('/reclamation/{id}', name: 'app_reclamation_list')]
    public function list(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/listereclamation.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    #[Route('/reclamation/{id}/edit', name: 'app_reclamation_edit')]
public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRep): Response
{
    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // process the form data
        $reclamationRep->save($reclamation, true);
        return $this->redirectToRoute('app_reclamation_list', ['id' => $reclamation->getId()]);
    }
    return $this->render('reclamation/editreclamation.html.twig', [
        'form' => $form->createView(),
    ]);
    }

    #[Route('/reclamation/{id}/delete', name: 'app_reclamation_delete')]    
    public function removeArticle(
        int $id,
        ReclamationRepository $reclamationRepo
    )
    {
        
        $reclamation=$reclamationRepo->find($id);
        
        $reclamationRepo->remove($reclamation,true);

        return $this->redirectToRoute('app_reclamation', ['id' => $id]);
  
    }
}
