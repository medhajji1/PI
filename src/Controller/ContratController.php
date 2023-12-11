<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contrat')]
class ContratController extends AbstractController
{
    #[Route('/', name: 'app_contrat_index', methods: ['GET'])]
    public function index(ContratRepository $contratRepository): Response
    {
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contratRepository->findAll(),
        ]);
    }

    #[Route('/newcontrat', name: 'app_contrat_new', methods: ['GET', 'POST'])]
public function new(Request $request, ContratRepository $contratRepository): Response
{
    $contrat = new Contrat();
    $form = $this->createForm(ContratType::class, $contrat);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $contratRepository->save($contrat, true);

        return $this->redirectToRoute('app_paiement_new', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('contrat/new.html.twig', [
        'contrat' => $contrat,
        'form' => $form,
    ]);
}

#[Route('/create', name: 'app_contrat_create', methods: ['POST'])]
public function create(Request $request, ContratRepository $contratRepository): Response
{
    $contrat = new Contrat();
    $form = $this->createForm(ContratType::class, $contrat);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $contratRepository->save($contrat, true);

        return $this->redirectToRoute('app_paiement_new', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('contrat/new.html.twig', [
        'contrat' => $contrat,
        'form' => $form,
    ]);
}

    #[Route('/ajout', name: 'app_contrat_ajoutF', methods: ['GET', 'POST'])]
    public function ajoutF(Request $request, ContratRepository $contratRepository): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->save($contrat, true);

            return $this->redirectToRoute('app_paiement_new_f', ['idContrat'=>$contrat->getIdcontrat()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/ajoutF.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{idcontrat}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    #[Route('/{idcontrat}/edit', name: 'app_contrat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->save($contrat, true);

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{idcontrat}', name: 'app_contrat_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getIdcontrat(), $request->request->get('_token'))) {
            $contratRepository->remove($contrat, true);
        }

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }
}
//symfony 5.4 table category (id,name) quelles sont les etape 