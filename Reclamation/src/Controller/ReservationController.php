<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reservations = $entityManager
            ->getRepository(Reservation::class)
            ->findAll();

        return $this->render('voiture/reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    
    #[Route('/new', name: 'app_reservation_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $date_d = $reservation->getDateD();
            $date_f = $reservation->getDateF();

            if ($date_d > $date_f) {
                $this->addFlash('error', 'La date de début doit être inférieure à la date de fin');
                return $this->redirectToRoute('app_reservation_create');
            }

            $now = new \DateTime('now');

            if ($date_d < $now) {
                $this->addFlash('error', 'La date de début doit être supérieure à la date du jour');
                return $this->redirectToRoute('app_reservation_create');
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            $sid = 'ACd5424da929038840707cf2d555e6f815';
            $token = '6349bb28041da0ea759d771e3a49c217';
            $twilioPhoneNumber = '+16206478661';
            $recipientPhoneNumber = '+21698136779';                
            $client = new Client($sid, $token);
            $client->messages->create(
                $recipientPhoneNumber,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => "Nouveau Reservation pour la voiture ".$reservation->getVoiture()->getImmatriculation()." du ".$reservation->getDateD()->format('d/m/Y')." au ".$reservation->getDateF()->format('d/m/Y'),
                ]
            );
            // if successful

            $this->addFlash('success', 'La réservation a bien été créée');

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/create-reservation.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservation_create', methods: ['GET', 'POST'])]
    public function createForSpecificVoiture(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        $id = $request->attributes->get('id');

        if ($form->isSubmitted() && $form->isValid()) {

            $date_d = $reservation->getDateD();
            $date_f = $reservation->getDateF();

            if ($date_d > $date_f) {
                $this->addFlash('error', 'La date de début doit être inférieure à la date de fin');
                return $this->redirectToRoute('app_reservation_create');
            }

            $now = new \DateTime('now');

            if ($date_d < $now) {
                $this->addFlash('error', 'La date de début doit être supérieure à la date du jour');
                return $this->redirectToRoute('app_reservation_create');
            }

            $entityManager->persist($reservation);
            $entityManager->flush();

            $sid = 'ACd5424da929038840707cf2d555e6f815';
            $token = '6349bb28041da0ea759d771e3a49c217';
            $twilioPhoneNumber = '+16206478661';
            $recipientPhoneNumber = '+21698136779';                
            $client = new Client($sid, $token);
            $client->messages->create(
                $recipientPhoneNumber,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => "Nouveau Reservation pour la voiture ".$reservation->getVoiture()->getImmatriculation()." du ".$reservation->getDateD()->format('d/m/Y')." au ".$reservation->getDateF()->format('d/m/Y'),
                ]
            );
            $this->addFlash('success', 'La réservation a bien été créée');

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/create-reservation-for-voiture.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'id' => $id
        ]);
    }


    

    #[Route('/delete/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/edit/{id}', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/edit-reservation.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }


    // // Category

    // #[Route('/category', name: 'app_category_index', methods: ['GET', 'POST'])]
    // public function category(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $categories = $entityManager
    //         ->getRepository(Category::class)
    //         ->findAll();

    //     $category = new Category();
    //     $form = $this->createForm(CreateCategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($category);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('voiture/category.html.twig', [
    //         'category' => $category,
    //         'form' => $form,
    //         'categories' => $categories,
    //     ]);
    // }


    // #[Route('/category/{id}', name: 'app_category_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CreateCategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('voiture/edit-category.html.twig', [
    //         'category' => $category,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/category/delete/{id}', name: 'app_category_delete', methods: ['POST'])]
    // public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($category);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    // }



    // // Reservations
}
