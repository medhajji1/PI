<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\HttpFoundation\Request;

class QrCodeGeneratorController extends AbstractController
{
    #[Route('/qr/{slug}', name: 'app_qr_codes')]
    public function index(Request $request): Response
    {

        $slug = "http://localhost:8000/reservation/new/" . $request->get('slug');
        $writer = new PngWriter();
        $qrCode = QrCode::create($slug)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(450)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $label = Label::create('')->setFont(new NotoSans(8));
 
        $qrCodes = [];
        $qrCodes['simple'] = $writer->write(
                                $qrCode,
                                null,
                                $label->setText('')
                            )->getDataUri();
 
        $qrCode->setForegroundColor(new Color(255, 0, 0));
        $qrCodes['changeColor'] = $writer->write(
            $qrCode,
            null,
            $label->setText('Color Change')
        )->getDataUri();
 
        $qrCode->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 0, 0));
        $qrCodes['changeBgColor'] = $writer->write(
            $qrCode,
            null,
            $label->setText('Background Color Change')
        )->getDataUri();
 
        $qrCode->setSize(200)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
 

        return $this->render('qr_code_generator/index.html.twig', $qrCodes);
    }
}
