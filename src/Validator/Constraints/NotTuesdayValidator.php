<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class NotTuesdayValidator extends ConstraintValidator
{
    /**
     * @param DateTime $visitdate
     */
    public function validate($visitdate, Constraint $constraint): void
    {
        if (carbon($visitdate)->isTuesday()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
