<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    #[Route('/photo', name: 'app_photo')]
    public function index(): Response
    {
        $filename = '/var/www/html/raspberry-photobox/public/assets/IMG'.date('m-d-Y').'.JPG';
        shell_exec("sudo gphoto2 --wait-event=1s  --set-config autofocusdrive=1 --set-config eosremoterelease=5 --wait-event-and-download=2s --filename /var/www/html/raspberry-photobox/public/assets/IMG_%d-%m-%Y_%H-%M.JPG --set-config eosremoterelease=4");
        return $this->render('photo/index.html.twig', [
            'controller_name' => 'PhotoController',
            'file' => $filename
        ]);
    }
}
