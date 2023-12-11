<?php
namespace App\Controller;
use Google\Client;
use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use App\Form\ReclamationType;
use App\Entity\Reponse;
use App\Form\ReponseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\JsonResponse;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\AlternativePart;
use Symfony\Component\Mime\Part\Multipart\MixedPart;
use App\Service\GmailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
class DashboardReclamationController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard_reclamation/index.html.twig', [
            'controller_name' => 'DashboardReclamationController',
        ]);
    }
    #[Route('/dashboard/reclamation/{filter}', name: 'app_dashboard_reclamation', defaults: ['filter' => ''], methods:['GET'])]
    public function list(?string $filter = null,EntityManagerInterface $entityManager ): Response
    {
        if(!empty($filter)){
            $reclamations = $entityManager->getRepository(Reclamation::class)->tri($filter);
        } else {
            $reclamations = $entityManager->getRepository(Reclamation::class)->findAll();
        }
        return $this->render('dashboard_reclamation/listereclamation.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/dashboard/reclamation/{id}/delete', name: 'app_dashboard_reclamation_delete')]
public function delete(EntityManagerInterface $entityManager, Reclamation $reclamation): Response
        {
            $entityManager->remove($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_reclamation');
        }
        #[Route('/dashboard/reclamation/{id}/edit', name: 'app_dashboard_reclamation_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Reclamation $reclamation): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setStatus('OPEN');
            $reclamation->setSeverityLevel('LOW');
            $entityManager->flush();

            $this->addFlash('success', 'Reclamation a ete modifier.');

            return $this->redirectToRoute('app_dashboard_reclamation');
        }

        return $this->render('dashboard_reclamation/editreclamation.html.twig', [
            'form' => $form->createView(),
        ]);
    }
        #[Route('/dashboard/reclamation/{id}/reponse', name: 'app_dashboard_reclamation_reponse')]
    public function addReponse(Request $request, EntityManagerInterface $entityManager, Reclamation $reclamation, MailerInterface $mailer): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setIdReclamation($reclamation);
            $reclamation->setStatus('OPEN');
            $reclamation->setSeverityLevel('LOW');
            $entityManager->persist($reponse);
            $entityManager->flush();
            //pdf m33aha
            
            $ms = new GmailSmtpTransport('mohamedhadji603@gmail.com', 'svjhlzjcnxisriga'); 
            $mailer = new Mailer($ms);
            $message = (new TemplatedEmail())
            
                ->from('mohamedhadji603@gmail.com')
                ->to($reclamation->getEmail())
                ->subject($reclamation->getMessage())
                ->html("<html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 16px;
                            line-height: 1.5;
                            background-color: #f5f5f5;
                        }
                        h1 {
                            color: #007bff;
                            font-size: 24px;
                            font-weight: bold;
                        }
                        p {
                            color: #333;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                            background-color: #fff;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0,0,0,0.2);
                        }
                    </style>
                </head>
                <body><h1>Bonjour,</h1><p>Votre réclamation a été bien reçue. Nous allons la traiter dans les plus brefs délais et nous reviendrons vers vous dès que possible.</p><p>Cordialement,</p><p>L'équipe de TuniRent</p></body></html>");                          
                $mailer->send($message);
            return $this->redirectToRoute('app_dashboard_reclamation');
        }
        return $this->render('dashboard_reclamation/reponse.html.twig', [
            'form' => $form->createView(),
            'reclamation' => $reclamation,
        ]);
    }
 #[Route('/search', name: 'ajax_search',methods:['GET'])]
public function search(Request $request, ReclamationRepository $reclamationRepository)
{
    $query = $request->query->get('q');

    $reclamations = $reclamationRepository->findByNom($query);

    $results = [];
    foreach ($reclamations as $reclamation) {
        $results[] = [
            'id' => $reclamation->getId(),
            'nom' => $reclamation->getNom(),            
            'email' => $reclamation->getEmail(),
            'numtel' => $reclamation->getNumtel(),
            'message' => $reclamation->getMessage(),
            'date' => $reclamation->getDateSubmitted()->format('Y-m-d H:i:s'),
            'status' => $reclamation->getStatus(),
            'category' => $reclamation->getCategory(),
        ];
    }

    return new JsonResponse($results);
}
    #[Route('/mail', name: 'mail')]
        public function sendEmail(GmailService $service): Response
        {
            
            $service->sendMail("test","mohamedhadji303@gmail.com");
            return new Response();
        }

        //mobile 

        #[Route('/dashboardmobile/reclamation/{filter}', name: 'app_dashboard_reclamationmobile', defaults: ['filter' => ''], methods:['GET'])]
public function listmobile(?string $filter = null, EntityManagerInterface $entityManager): Response
{
    if(!empty($filter)){
        $reclamations = $entityManager->getRepository(Reclamation::class)->tri($filter);
    } else {
        $reclamations = $entityManager->getRepository(Reclamation::class)->findAll();
    }

    $data = [];
    foreach($reclamations as $reclamation) {
        $data[] = [
            'id' => $reclamation->getId(),
            'nom' => $reclamation->getNom(),
            'sujet' => $reclamation->getSujet(),
            'message' => $reclamation->getMessage(),
            'email' => $reclamation->getEmail(),
            'status' => $reclamation->getStatus(),
            'severityLevel' => $reclamation->getSeverityLevel()
        ];
    }
    return new JsonResponse($data);
}
#[Route('/dashboardmobile/reclamation/{id}/delete', name: 'app_dashboard_reclamationmobile_delete')]
public function deletemobile(EntityManagerInterface $entityManager, Reclamation $reclamation): JsonResponse
{
    $entityManager->remove($reclamation);
    $entityManager->flush();

    $response = [
        'status' => 'success',
        'message' => 'Reclamation deleted successfully'
    ];

    return new JsonResponse($response);
}
}
