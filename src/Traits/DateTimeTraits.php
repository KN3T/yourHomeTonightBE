<?php

namespace App\Traits;

trait DateTimeTraits
{
    public function timestampToDateTime(int $timestamp): \DateTime
    {
        return \DateTime::createFromFormat('Y/m/d', date('Y/m/d', $timestamp));
    }
}
