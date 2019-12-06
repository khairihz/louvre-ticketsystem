<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class HalfDay extends Constraint
{
    /**
     * @var string
     */
    public $message = 'validator.half_day';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
