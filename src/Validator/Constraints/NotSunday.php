<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class NotSunday extends Constraint
{
    /**
     * @var string
     */
    public $message = 'validator.not_sunday';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
