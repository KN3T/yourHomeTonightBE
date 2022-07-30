<?php

namespace App\Tests\Entity;

use App\Entity\Address;
use App\Entity\Hotel;
use Monolog\DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    /**
     * @return void
     *
     */
    public function testAddress(): void
    {
        $address = new Address();
        $hotel = new Hotel();
        $date = new DateTimeImmutable('now');
        $address->setAddress('123 Main St');
        $address->setCity('New York');
        $address->setProvince('NY');
        $address->setHotel($hotel);
        $address->setCreatedAt($date);
        $address->setUpdatedAt($date);
        $this->assertEquals('123 Main St', $address->getAddress());
        $this->assertEquals('New York', $address->getCity());
        $this->assertEquals('NY', $address->getProvince());
        $this->assertEquals($hotel, $address->getHotel());
        $this->assertEquals($date, $address->getCreatedAt());
        $this->assertEquals($date, $address->getUpdatedAt());
        $this->assertEquals(null, $address->getId());

    }
}
