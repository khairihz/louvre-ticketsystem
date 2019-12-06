<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class TicketsLimit extends Constraint
{
    /**
     * @var string
     */
    public $message = 'validator.not.more.thousand';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
