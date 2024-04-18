<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Transport;

class MailerController extends AbstractController
{
    #[Route("/email", name: "email")]
    public function sendEmail(): Response
    {
        // Encoder le mot de passe avec urlencode
        $password = '<?php echo "kamel"; ?>';
        $encoded_password = urlencode($password);

        // Paramètres SMTP Gmail avec mot de passe encodé et nom d'utilisateur
        $username = 'abbassi.kamel'; // Remplacez par votre nom d'utilisateur
        $dsn = sprintf('smtp://smtp.gmail.com:587?encryption=starttls&auth_mode=login&username=%s&password=%s', $username, $encoded_password);

        // Créer un transport SMTP à partir du DSN
        $transport = Transport::fromDsn($dsn);

        // Créer une instance de Mailer avec le transport SMTP
        $mailer = new Mailer($transport);

        // Créer un objet Email
        $email = (new Email())
            ->from('your_email@gmail.com')
            ->to('abbassi.kamel@gmail.com')
            ->subject('Your Subject')
            ->text('Your Email Body');

        // Envoyer l'e-mail
        $mailer->send($email);

        return new Response('Email sent successfully!');
    }
}
