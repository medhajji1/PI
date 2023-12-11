<?php

namespace App\Controller;

use App\Entity\Voiture; 
use App\Entity\Reservation;
use App\Form\VoitureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rate')]
class RateVoitureController extends AbstractController
{
}
