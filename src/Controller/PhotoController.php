<?php

namespace App\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    const ABS_FOLDER = "/var/www/html/raspberry-photobox/public";
    const FULL_PATH = "/var/www/html/raspberry-photobox/public/assets/images";
    #[Route('/photo', name: 'app_photo')]
    public function index(): Response
    {
        //$photoname = 'IMG10-29-2023.JPG';
        //$file = '/assets/images/IMG10-29-2023.JPG';

        $photoname = date('m-d-Y').'.JPG';
        $file = '/assets/images/IMG'. $photoname;
        $filename = self::ABS_FOLDER . $file;
        shell_exec("sudo gphoto2 --wait-event=1s  --set-config autofocusdrive=1 --set-config eosremoterelease=5 --wait-event-and-download=2s --filename $filename --set-config eosremoterelease=4");
        return $this->render('photo/index.html.twig', [
            'controller_name' => 'PhotoController',
            'file' => $file,
            'photoname' => $photoname
        ]);
    }

    #[Route('/photo/delete/{photo}', name: 'app_photo')]
    public function delete(Request $request): Response
    {
        try {
            $file = self::FULL_PATH . '/' . $request->attributes->get('photo') ?? throw new \Exception('no file');
            unlink($file);
        }catch (\Exception $exception){
            return $this->redirect('/');
        }
        return $this->redirect('/');
    }

    #[Route('/photo/save/{photo}', name: 'app_photo')]
    public function save(Request $request): Response
    {
        $fileFullPath = self::FULL_PATH . '/' . $request->attributes->get('photo');
        $file = '/assets/images/IMG' . $request->attributes->get('photo');
        return $this->render('photo/save.html.twig', [
            'filefullPath' => $fileFullPath,
            'file' => $file
        ]);
    }

    #[Route('/photo/send', name: 'app_photo')]
    public function send(Request $request, MailerInterface $mailer): Response
    {
	
        $email = (new Email())
            ->from('blickfangkosmetik-adventsbasar@gmx.de')
            ->to($_POST['email'])
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return $this->redirect('/');
    }
}
