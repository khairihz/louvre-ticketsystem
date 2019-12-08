<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class NotHolidaysValidator extends ConstraintValidator
{
    /**
     * @param DateTime $visit
     */
    public function validate($visit, Constraint $constraint): void
    {
        foreach (holidays($visit) as $holiday) {
            if ($holiday->format('Y-m-d') === $visit->format('Y-m-d')) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
