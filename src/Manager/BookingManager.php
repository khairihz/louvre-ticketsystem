<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Booking;
use App\Entity\Ticket;
use App\Exception\NoBookingFoundException;
use App\Service\AgeCalculator;
use App\Service\Mailer;
use App\Service\Payment;
use App\Service\PriceCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookingManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var PriceCalculator
     */
    private $price;

    /**
     * @var AgeCalculator
     */
    private $age;

    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, PriceCalculator $price, AgeCalculator $age, Payment $payment, Mailer $mailer)
    {
        $this->em = $em;
        $this->session = $session;
        $this->price = $price;
        $this->age = $age;
        $this->payment = $payment;
        $this->mailer = $mailer;
    }

    public function getBooking(): Booking
    {
        try {
            $booking = $this->getCurrentBooking();
        } catch (NoBookingFoundException $e) {
            $booking = new Booking();
            $this->session->set('booking', $booking);
        }

        return $booking;
    }

    public function initialize(Booking $booking): void
    {
        while (count($booking->getTickets()) !== $booking->getNumberOfTickets()) {
            if (count($booking->getTickets()) > $booking->getNumberOfTickets()) {
                $booking->removeTicket($booking->getTickets()->last());
            } else {
                $booking->addTicket(new Ticket());
            }
        }
    }

    public function setTicketsPrices(Booking $booking): void
    {
        $tickets = $booking->getTickets();
        $totalPrice = 0;

        /** @var Ticket $ticket */
        foreach ($tickets as $ticket) {
            $age = $this->age->calculate($booking->getVisit(), $ticket->getBirthdate());
            $ticket->setAge($age);

            $price = $this->price->calculate($ticket->getReduceRate(), $ticket->getAge(), $booking->getTypeOfTicket());
            $ticket->setPrice($price);

            $totalPrice += $ticket->getPrice();
        }

        $booking->setPrice($totalPrice);
    }

    public function charge(Request $request, Booking $booking): void
    {
        $transactionId = $this->payment->process($booking, $request->request->get('stripe-token'));

        $booking->setTransactionId($transactionId);
        $this->em->persist($booking);
        $this->em->flush();

        $this->mailer->send($booking);
    }

    /**
     * @throws NoBookingFoundException
     */
    public function getCurrentBooking(): Booking
    {
        $booking = $this->session->get('booking');

        if (null === $booking) {
            throw new NoBookingFoundException('No booking found.');
        }

        return $booking;
    }
}
