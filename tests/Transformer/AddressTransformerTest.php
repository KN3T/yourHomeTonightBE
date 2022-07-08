<?php

namespace App\Tests\Transformer;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Transformer\AddressTransformer;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AddressTransformerTest extends TestCase
{
    /**
     * @var AddressTransformer
     */
    private $addressTransformer;

    protected function setUp(): void
    {
        $this->addressTransformer = new AddressTransformer();
    }

    public function testTransform(): void
    {
        $address = new Address();
        $date = new DateTimeImmutable('now');
        $address->setAddress('123 Main St');
        $address->setCity('New York');
        $address->setProvince('NY');
        $address->setHotel(new Hotel());
        $address->setCreatedAt($date);
        $address->setUpdatedAt($date);

        $addressTransformed = $this->addressTransformer->toArray($address);

        $this->assertEquals(null, $addressTransformed['id']);
        $this->assertEquals('123 Main St', $addressTransformed['address']);
        $this->assertEquals('New York', $addressTransformed['city']);
        $this->assertEquals('NY', $addressTransformed['province']);

        $address2 = new Address();
        $address2->setAddress('123 Main St');
        $address2->setCity('New York');
        $address2->setProvince('NY');
        $address2->setHotel(new Hotel());
        $address2->setCreatedAt($date);
        $address2->setUpdatedAt($date);

        $addressArray = [$address, $address2];
        $addressTransformedArray = $this->addressTransformer->listToArray($addressArray);

        $expectedArray = [
            [
                "id" => null,
                "city" => "New York",
                "province" => "NY",
                "address" => "123 Main St",
            ],
            [
                "id" => null,
                "city" => "New York",
                "province" => "NY",
                "address" => "123 Main St",
            ],
        ];
        $this->assertEquals($expectedArray, $addressTransformedArray);
    }
}
