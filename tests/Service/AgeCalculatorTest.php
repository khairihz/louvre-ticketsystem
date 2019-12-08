<?php

namespace App\Tests\Service;

use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use App\Service\AgeCalculator;

class AgeCalculatorTest extends TestCase
{
    private $calculator;

    public function setUp(): void
    {
        $this->calculator = new AgeCalculator();
    }

    /**
     * @dataProvider provideData
     */
    public function testCalculate(DateTimeInterface $visit, DateTimeInterface $birth, int $expected): void
    {
        $age = $this->calculator->calculate($visit, $birth);

        static::assertSame($expected, $age);
    }

    public function provideData(): iterable
    {
        yield [
            DateTime::createFromFormat('d-m-Y','12-02-2019'),
            DateTime::createFromFormat('d-m-Y','12-02-2019'),
            0
        ];

        yield [
            DateTime::createFromFormat('d-m-Y','12-02-2019'),
            DateTime::createFromFormat('d-m-Y','12-02-1999'),
            20
        ];

        yield [
            DateTime::createFromFormat('d-m-Y','12-02-2009'),
            DateTime::createFromFormat('d-m-Y','12-02-1900'),
            109
        ];

        yield [
            DateTime::createFromFormat('d-m-Y','01-11-2019'),
            DateTime::createFromFormat('d-m-Y','03-09-1999'),
            20
        ];

        yield [
            DateTime::createFromFormat('d-m-Y','12-02-2019'),
            DateTime::createFromFormat('d-m-Y','12-02-2020'),
            0
        ];
    }
}
