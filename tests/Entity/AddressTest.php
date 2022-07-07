<?php

namespace App\Tests\Entity;

use App\Entity\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    // function to test the Address entity
    public function testAddress()
    {
        $address = new Address();
        $address->setAddress('123 Main St');
    }
}
