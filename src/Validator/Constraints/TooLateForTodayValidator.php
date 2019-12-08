<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Booking;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class TooLateForTodayValidator extends ConstraintValidator
{
    public const CLOSE_TIME = 18;

    /**
     * Checks if the passed value is valid.
     *
     * @param Booking $booking
     */
    public function validate($booking, Constraint $constraint): void
    {
        if ($booking->getVisit()->format('H') >= static::CLOSE_TIME) {
            $this->context->buildViolation($constraint->message)
                ->atPath('visitDate')
                ->addViolation();
        }
    }
}
