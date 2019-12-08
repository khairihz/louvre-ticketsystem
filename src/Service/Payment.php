<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Booking;
use Stripe\Charge;
use Stripe\Stripe;
use App\Stripe\StripeLogger;
use Stripe\Exception\ExceptionInterface;
use App\Exception\PaymentFailureException;

final class Payment
{
    private $secret;
    private $logger;

    public function __construct(string $secret, StripeLogger $logger)
    {
        $this->secret = $secret;
        $this->logger = $logger;
        Stripe::setLogger($logger);
    }

    /**
     * @throws PaymentFailureException
     */
    public function process(Booking $booking, string $token): string
    {
        Stripe::setApiKey($this->secret);

        // Create a charge: this will charge the user's card
        try {
            $charge = Charge::create([
                'amount' => $booking->getPrice() * 100, // Amount in cents
                'currency' => 'eur',
                'source' => $token,
                'description' => 'Billetterie du Louvre',
            ]);

            if ($charge->paid || $charge->captured) {
                return $charge->id;
            }

            throw new PaymentFailureException($charge->failure_message, $charge->failure_code);
        } catch (ExceptionInterface $e) {
            $this->logger->error(sprintf('Unable to process payment : %s', $e->getMessage()), ['exception' => $e, 'booking' => $booking]);
            throw new PaymentFailureException('Unable to process payment');
        }
    }
}
