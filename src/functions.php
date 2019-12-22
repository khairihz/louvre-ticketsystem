<?php

declare(strict_types=1);

use Carbon\Carbon;
use Yasumi\Provider\AbstractProvider;
use Yasumi\Yasumi;

function carbon(?DateTimeInterface $date = null): Carbon
{
    if (null === $date) {
        return Carbon::now();
    }

    if ($date instanceof Carbon) {
        return clone $date;
    }

    return Carbon::createFromFormat(DateTimeInterface::ATOM, $date->format(DateTimeInterface::ATOM));
}

function holidays(?DateTimeInterface $date, string $region = 'France'): AbstractProvider
{
    $year = (int)carbon($date)->format('Y');
    $year = $year < 1000 ? 1000 : $year;
    return Yasumi::create($region, $year);
}
