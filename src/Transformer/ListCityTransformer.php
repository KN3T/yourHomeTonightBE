<?php

namespace App\Transformer;

class ListCityTransformer
{
    public function toArray(array $city): array
    {
        dd($city);
    }

    public function listToArray(array $cityList): array
    {
        $result = [];
        foreach ($cityList as $city) {
            $result[] = $this->toArray($city);
        }

        return $result;
    }
}
