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

    return Carbon::createFromFormat(DateTimeInterface::ATOM, $date->format(DateTimeInterface::ATOM));
}

function holidays(?DateTimeInterface $date, string $region = 'France'): AbstractProvider
{
    return Yasumi::create($region, (int)carbon($date)->format('Y'));
}
