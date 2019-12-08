<?php

namespace App\Tests\Service;

use App\Entity\Booking;
use PHPUnit\Framework\TestCase;
use App\Service\PriceCalculator;

class PriceCalculatorTest extends TestCase
{
    private $calculator;

    public function setUp(): void
    {
        $this->calculator = new PriceCalculator();
    }

    /**
     * @dataProvider provideData
     */
    public function testCalculate(bool $reduceRate, int $age, int $typeOfTicket, int $expected): void
    {
        $price = $this->calculator->calculate($reduceRate, $age, $typeOfTicket);

        static::assertSame($expected, $price);
    }

    public function provideData(): array
    {
        return [
            [true, 13, Booking::TYPE_OF_TICKET_HALF_DAY, PriceCalculator::REDUCE_PRICE / 2],
            [true, 13, Booking::TYPE_OF_TICKET_DAY, PriceCalculator::REDUCE_PRICE],
            [true, 61, Booking::TYPE_OF_TICKET_HALF_DAY, PriceCalculator::REDUCE_PRICE / 2],
            [true, 61, Booking::TYPE_OF_TICKET_DAY, PriceCalculator::REDUCE_PRICE],
            [false, 13, Booking::TYPE_OF_TICKET_HALF_DAY, PriceCalculator::ADULT_PRICE / 2],
            [false, 13, Booking::TYPE_OF_TICKET_DAY, PriceCalculator::ADULT_PRICE],
            [false, 61, Booking::TYPE_OF_TICKET_HALF_DAY, PriceCalculator::SENIOR_PRICE / 2],
            [false, 61, Booking::TYPE_OF_TICKET_DAY, PriceCalculator::SENIOR_PRICE],
            [false, 11, Booking::TYPE_OF_TICKET_HALF_DAY, PriceCalculator::CHILD_PRICE / 2],
            [false, 11, Booking::TYPE_OF_TICKET_DAY, PriceCalculator::CHILD_PRICE],
            [false, 2, Booking::TYPE_OF_TICKET_HALF_DAY, PriceCalculator::BABY_PRICE / 2],
            [false, 2, Booking::TYPE_OF_TICKET_DAY, PriceCalculator::BABY_PRICE],
        ];
    }
}