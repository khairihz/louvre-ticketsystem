<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class TooLateForToday extends Constraint
{
    /**
     * @var string
     */
    public $message = 'validator.too_late_for_today';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
