<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeInterface;

final class AgeCalculator
{
    public function calculate(DateTimeInterface $visitDate, DateTimeInterface $birthDate): int
    {
        if ($birthDate > $visitDate) {
            return 0;
        }

        return carbon($birthDate)->diff($visitDate)->y;
    }
}
