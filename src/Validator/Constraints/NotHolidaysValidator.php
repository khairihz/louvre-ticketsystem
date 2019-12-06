<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class NotHolidaysValidator extends ConstraintValidator
{
    /**
     * @param DateTime $visitdate
     */
    public function validate($visitdate, Constraint $constraint): void
    {
        foreach (holidays($visitdate) as $holiday) {
            if ($holiday->format('Y-m-d') === $visitdate->format('Y-m-d')) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
