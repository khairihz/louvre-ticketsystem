<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\BookingType;
use App\Form\TicketsType;
use App\Manager\BookingManager;
use App\Exception\PaymentFailureException;
use App\Exception\NoBookingFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/{_locale}", requirements={"_locale": "fr|en"})
 */
class BookingController extends AbstractController
{
    /**
     * @var string
     */
    private $stripePublicKey;

    public function __construct(string $stripePublicKey)
    {
        $this->stripePublicKey = $stripePublicKey;
    }

    /**
     * @Route("/", name="homepage", methods={"GET", "POST"})
     */
    public function indexAction(Request $request, BookingManager $bookingManager): Response
    {
        $booking = $bookingManager->getBooking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingManager->initialize($booking);

            return $this->redirectToRoute('ticket');
        }

        return $this->render('booking/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ticket", name="ticket", methods={"GET", "POST"})
     */
    public function ticketAction(Request $request, BookingManager $bookingManager): Response
    {
        $booking = $bookingManager->getBooking();

        $form = $this->createForm(TicketsType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingManager->setTicketsPrices($booking);

            return $this->redirectToRoute('summary');
        }

        return $this->render('booking/ticket.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/summary", name="summary", methods={"GET", "POST"})
     */
    public function summaryAction(Request $request, BookingManager $bookingManager, TranslatorInterface $translator): Response
    {
        $booking = $bookingManager->getBooking();

        if (Request::METHOD_POST === $request->getMethod()) {
            try {
                $bookingManager->charge($request, $booking);
                $this->addFlash('success', $translator->trans('payment_message.success', ['%email%' => $booking->getEmail()]));

                return $this->redirectToRoute('final_summary');
            } catch (PaymentFailureException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('booking/summary.html.twig', [
            'booking' => $booking,
            'stripe_public_key' => $this->stripePublicKey,
        ]);
    }

    /**
     * @Route("/final-summary", name="final_summary", methods={"GET", "POST"})
     */
    public function finalSummaryAction(BookingManager $bookingManager): Response
    {
        try {
            $booking = $bookingManager->getCurrentBooking();
        } catch (NoBookingFoundException $e) {
            return $this->redirectTo('homepage');
        }

        // Clear session.
        $this->get('session')->clear();

        return $this->render('booking/final-summary.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/sale", name="sale", methods={"GET"})
     */
    public function saleAction(): Response
    {
        return $this->render('static/general-terms-of-sale.html.twig');
    }

    /**
     * @Route("/news", name="news", methods={"GET"})
     */
    public function newsAction(): Response
    {
        return $this->render('static/practical-news.html.twig');
    }

    /**
     * @Route("/legal-mentions", name="legal_mentions", methods={"GET"})
     */
    public function mentionsAction(): Response
    {
        return $this->render('static/legal-mentions.html.twig');
    }
}
