<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Booking;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Exception\ExceptionInterface;

final class Payment
{
    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function process(Booking $booking, string $token): ?string
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

            return $charge['id'];
        } catch (ExceptionInterface $e) {
            /**
             * Payment failure due to either invalid bank details, fraud,
             * SCA ( strong customer authentication ) requirement ... etc.
             *
             * probably should be handled in a nicer way.
             */

            return null;
        }
    }
}
