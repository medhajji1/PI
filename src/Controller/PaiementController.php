<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Repository\ContratRepository;
use App\Repository\PaiementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\StripeClient;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;

#[Route('/paiement')]
class PaiementController extends AbstractController
{

    private $stripe;

    public function __construct(StripeClient $stripe)
    {
        $this->stripe = $stripe;
    }

    #[Route('/', name: 'app_paiement_index', methods: ['GET'])]
    public function index(Request $request, PaiementRepository $paiementRepository): Response
    {
        $sort = $request->query->get('sort');
        $search = $request->query->get('search');

        $paiements = $paiementRepository->findBySearchAndSort($search, $sort);

        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiements,
        ]);
    }


    #[Route('/new', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaiementRepository $paiementRepository): Response
    {
        $paiement = new Paiement();
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paiementRepository->save($paiement, true);

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }
    #[Route('/ajouter', name: 'app_paiement_new_f', methods: ['GET', 'POST'])]
    public function newf(MailerInterface $mailer,Request $request, PaiementRepository $paiementRepository ,ContratRepository $contratRepository, SessionInterface $session): Response
    {
        $paiement = new Paiement();
        //$paiement->setIdcontrat($contratRepository->find($request->query->get('idContrat')));
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->remove('idcontrat');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paiementRepository->save($paiement, true);
            $session = new Session();
            $flashBag = $session->getFlashBag();
            $flashBag->add('success', 'mail sent to your email to complete payment.');
            $email = "zayani.fedi@esprit.tn";
            $intent = $this->stripe->paymentIntents->create([
                'amount' => $paiement->getMontant(),
                'currency' => 'usd',
            ]);

            // Generate a payment link
            $link = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $paiement->getMotif(),
                            ],
                            'unit_amount' => $paiement->getMontant(),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => 'http://example.com/success',
                'cancel_url' => $this->generateUrl('app_paiement_delete', ['idpaiement' => $paiement->getIdpaiement()], UrlGeneratorInterface::ABSOLUTE_URL)
                //'payment_intent_data' => $intent->id,
            ])->url;
            
            $ms = new GmailSmtpTransport('mohamedhadji603@gmail.com', 'svjhlzjcnxisriga'); 
            $mailer = new Mailer($ms);
            $message = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to($email)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('proceed to payement!')
            ->text('paiement !')
            ->html('<a href="'.$link.'">lien pour valider paiement !</a>');

                $mailer->send($message);



        }

        return $this->renderForm('paiement/newF.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{idpaiement}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    #[Route('/{idpaiement}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement, PaiementRepository $paiementRepository): Response
    {
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paiementRepository->save($paiement, true);

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{idpaiement}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement, PaiementRepository $paiementRepository): Response
    {
            $paiementRepository->remove($paiement, true);


        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
