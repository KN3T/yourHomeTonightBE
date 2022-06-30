<?php

namespace App\Traits;

trait DateTimeTraits
{
    public function timestampToDateTime(int $timestamp): string
    {
        return date('Y/m/d', 1541843467);
    }
}
