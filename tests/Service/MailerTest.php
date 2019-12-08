<?php

namespace App\Tests\Service;

use Twig\Environment;
use App\Service\Mailer;
use App\Entity\Booking;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MailerTest extends TestCase
{
    public function testSend(): void
    {
        $mailerMock = $this->getMockBuilder(MailerInterface::class)
            ->getMock();
        $twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $booking = new Booking();
        $booking->setEmail('foo@bar.qux');

        $twigMock->expects($this->once())
            ->method('render')
            ->with('booking/booking-email.html.twig', [
                'booking' => $booking,
                'tickets' => $booking->getTickets()
            ])
            ->willReturn('content');

        $mailerMock->expects($this->once())
            ->method('send')
            ->willReturnCallback(static function(Email $email): void {
                /**
                 * @var \Symfony\Component\Mime\Address[] $from
                 * @var \Symfony\Component\Mime\Address[] $to
                 */
                $from = $email->getFrom();
                $to = $email->getTo();

                static::assertSame('Votre commande de billets d\'entrée pour le Louvre', $email->getSubject());
                static::assertSame('khairi.hezzi@gmail.com', $from[0]->toString());
                static::assertSame('foo@bar.qux', $to[0]->toString());
                static::assertSame('content', $email->getHtmlBody());
            });

        (new Mailer($mailerMock, $twigMock))->send($booking);
    }

}