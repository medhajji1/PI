<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PieChartController extends AbstractController
{
    #[Route('/dashboard/piechart', name: 'app_dashboard_piechart')]
    public function index(EntityManagerInterface $entityManager): Response
{

    $statusCount = $entityManager->getRepository(Reclamation::class)->getStatus();

    $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

    $jsonData = $serializer->serialize($statusCount, 'json');

    return $this->render('dashboard_reclamation/piechart.html.twig', [
        'statusCount' => $jsonData,
    ]);
}

#[Route('/dashboard/piechart/user', name: 'app_dashboard_utilisateur')]
public function userPie(EntityManagerInterface $entityManager): Response
{
$statusCount = $entityManager->getRepository(Utilisateur::class)->getRoles();



$serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

$jsonData = $serializer->serialize($statusCount, 'json');

return $this->render('utilisateur/back/piechartU.html.twig', [
    'statusCount' => $jsonData,
]);
}
}
