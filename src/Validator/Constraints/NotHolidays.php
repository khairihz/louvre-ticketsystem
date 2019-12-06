<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class NotHolidays extends Constraint
{
    /**
     * @var string
     */
    public $message = 'validator.not_holidays';
}
