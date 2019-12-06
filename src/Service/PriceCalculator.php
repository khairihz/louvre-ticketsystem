<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Booking;

final class PriceCalculator
{
    public const ADULT_PRICE = 16;

    public const BABY_PRICE = 0;

    public const CHILD_PRICE = 8;

    public const SENIOR_PRICE = 12;

    public const REDUCE_PRICE = 10;

    public function calculate(bool $reduceRate, int $age, int $typeOfTicket): int
    {
        $price = 0;

        if ($reduceRate && $age >= 12) {
            $price = self::REDUCE_PRICE;
        } else {
            switch ($age) {
                case $age < 4:
                    $price = self::BABY_PRICE;
                    break;
                case $age < 12:
                    $price = self::CHILD_PRICE;
                    break;
                case $age < 60:
                    $price = self::ADULT_PRICE;
                    break;
                case $age >= 60:
                    $price = self::SENIOR_PRICE;
                    break;
            }
        }

        if (Booking::TYPE_OF_TICKET_HALF_DAY === $typeOfTicket) {
            $price /= 2;
        }

        return $price;
    }
}
