<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class NotTuesdayValidator extends ConstraintValidator
{
    /**
     * @param DateTime $visit
     */
    public function validate($visit, Constraint $constraint): void
    {
        if (carbon($visit)->isTuesday()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
