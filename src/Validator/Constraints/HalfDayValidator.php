<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Booking;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class HalfDayValidator extends ConstraintValidator
{
    public const HALF_DAY = 14;

    /**
     * @param Booking $booking
     */
    public function validate($booking, Constraint $constraint): void
    {
        $date = date('H');

        if ($date >= static::HALF_DAY && $date < TooLateForTodayValidator::CLOSE_TIME && $booking->getVisit()->format('Y-m-d') === date('Y-m-d')) {
            $booking->setTypeOfTicket(Booking::TYPE_OF_TICKET_HALF_DAY);
            $this->context->buildViolation($constraint->message)
                ->atPath('typeOfTicket')
                ->addViolation();
        }
    }
}
