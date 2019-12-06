<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class NotTuesday extends Constraint
{
    /**
     * @var string
     */
    public $message = 'validator.not_tuesday';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
