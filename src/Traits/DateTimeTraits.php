<?php

namespace App\Traits;

use DateTime;

trait DateTimeTraits
{
    /**
     * @throws \Exception
     */
    public function timestampToDateTime(int $timestamp): DateTime
    {
        $stringDate = new DateTime(date('Y/m/d', $timestamp));

        return new DateTime(date('Y/m/d', $this->datetime2Timestamp($stringDate)));
    }

    public function datetime2Timestamp(\DateTimeInterface $dateTime): int
    {
        $stringDate = $dateTime->format('Y/m/d');

        return strtotime($stringDate);
    }

    public function diffDay($startDay, $endDay): int
    {
        return $startDay->diff($endDay)->format('%a');
    }
}
