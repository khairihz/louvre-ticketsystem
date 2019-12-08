<?php

declare(strict_types=1);

namespace App\Service;

use Twig\Environment;
use App\Entity\Booking;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

final class Mailer
{
    private $mailer;

    private $twig;

    /**
     * MailerService constructor.
     */
    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function send(Booking $booking): void
    {
        $mail = (new Email())
            ->subject('Votre commande de billets d\'entrée pour le Louvre')
            ->from('khairi.hezzi@gmail.com')
            ->to($booking->getEmail());

        $body = $this->twig->render('booking/booking-email.html.twig', [
            'booking' => $booking,
            'tickets' => $booking->getTickets(),
        ]);

        $mail->html($body);

        $this->mailer->send($mail);
    }
}
