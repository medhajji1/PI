<?php

namespace App\Controller;

use App\Entity\Voiture; 
use App\Entity\Reservation;
use App\Entity\VoitureRate;
use App\Form\VoitureType;
use App\Form\RateVoitureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/voiture')]
class VoitureController extends AbstractController
{
    #[Route('/', name: 'app_voiture_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {


        $query = "select v.immatriculation, c.marque, c.modele, v.kilometrage, v.couleur, v.image, sum(r.rating) as sum, count(r.voiture) as total from App\Entity\Category c, App\Entity\Voiture v left join App\Entity\VoitureRate r with v.immatriculation = r.voiture  where v.categorie = c.id group by v.immatriculation";
        $voitures = $entityManager
            ->createQuery($query)
            ->getResult();

        $voitures_count = count($voitures);
        $reservations_count = count($entityManager
            ->getRepository(Reservation::class)
            ->findAll());

        $query = "select count(v.immatriculation) as nb, c.marque, c.modele from App\Entity\Category c, App\Entity\Voiture v, App\Entity\Reservation r where v.categorie = c.id and v.immatriculation = r.voiture group by v.immatriculation order by nb desc";

        $top = $entityManager
            ->createQuery($query)
            ->getResult();

        if (count($top) > 0) {
            $top_reservation = $top[0]['marque'] . ' ' . $top[0]['modele'];
        } else {
            $top_reservation = "None";
        }

        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitures,
            'voitures_count' => $voitures_count,
            'reservations_count' => $reservations_count,
            'top_reservation' => $top_reservation,
        ]);
    }
    
    #[Route('/new', name: 'app_voiture_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // check if it is already in the database

            $does_voiture_exist = $entityManager
                ->getRepository(Voiture::class)
                ->findOneBy(['immatriculation' => $voiture->getImmatriculation()]);

            if ($does_voiture_exist) {
                $this->addFlash('error', 'Cette voiture existe déjà');
                return $this->renderForm('voiture/create-voiture.html.twig', [
                    'voiture' => $voiture,
                    'form' => $form,
                ]);
            }

            $entityManager->persist($voiture);
            $entityManager->flush();
            $this->addFlash('success', 'La voiture a été ajoutée avec succès');
            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/create-voiture.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }


    #[Route('/rate/{id}', name: 'app_voiture_rate', methods: ['GET', 'POST'])]
    public function rate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rate = new VoitureRate(); 
        $id = $request->get('id');

        $voiture = $entityManager
            ->getRepository(Voiture::class)
            ->findOneBy(['immatriculation' => $id]);
            
        $rate->setVoiture($voiture);
        $form = $this->createForm(RateVoitureType::class, $rate);
        $form->handleRequest($request);

        $voiture = $entityManager
            ->getRepository(Voiture::class)
            ->findOneBy(['immatriculation' => $id]);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rate);
            $entityManager->flush();
            $this->addFlash('success', 'Rating added successfully');
            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/rate.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_voiture_delete', methods: ['POST'])]
    public function delete(Request $request, Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voiture->getImmatriculation(), $request->request->get('_token'))) {
            
            echo $voiture;
            $entityManager->remove($voiture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/edit/{id}', name: 'app_voiture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voiture $voiture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/edit-voiture.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }
}
