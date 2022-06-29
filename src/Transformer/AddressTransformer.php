<?php

namespace App\Transformer;

use App\Entity\Address;

class AddressTransformer extends BaseTransformer
{
    public const ALLOW = ['id', 'city', 'province', 'address'];

    public function toArray(Address $address): array
    {
        return $this->transform($address, static::ALLOW);
    }

    public function listToArray(array $addresses): array
    {
        $result = [];
        foreach ($addresses as $address) {
            $result[] = $this->toArray($address);
        }

        return $result;
    }
}
