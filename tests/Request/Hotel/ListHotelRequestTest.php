<?php

namespace App\Tests\Request\Hotel;

use App\Request\Hotel\ListHotelRequest;
use App\Traits\DateTimeTraits;
use PHPUnit\Framework\TestCase;

class ListHotelRequestTest extends TestCase
{
    use DateTimeTraits;

    public function testGetOffset()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setOffset(1);
        $result = $listHotelRequest->getOffset();
        $this->assertEquals(1, $result);
    }

    public function testGetLimit()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setLimit(10);
        $result = $listHotelRequest->getLimit();
        $this->assertEquals(10, $result);
    }

    public function testGetSort()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setSortBy('price');
        $result = $listHotelRequest->getSortBy();
        $this->assertEquals('price', $result);
    }

    public function testGetOrder()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setOrder('asc');
        $result = $listHotelRequest->getOrder();
        $this->assertEquals('asc', $result);
    }

    public function testGetCity()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setCity('Can Tho');
        $result = $listHotelRequest->getCity();
        $this->assertEquals('Can Tho', $result);
    }

    public function testGetMaxPrice()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setMaxPrice(2.5);
        $result = $listHotelRequest->getMaxPrice();
        $this->assertEquals(2.5, $result);
    }

    public function testGetMinPrice()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setMinPrice(2.5);
        $result = $listHotelRequest->getMinPrice();
        $this->assertEquals(2.5, $result);
    }

    public function testGetAdults()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setAdults(2);
        $result = $listHotelRequest->getAdults();
        $this->assertEquals(2, $result);
    }

    public function testGetChildren()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setChildren(2);
        $result = $listHotelRequest->getChildren();
        $this->assertEquals(2, $result);
    }

    public function testGetBeds()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setBeds(2);
        $result = $listHotelRequest->getBeds();
        $this->assertEquals(2, $result);
    }

    public function testGetRating()
    {
        $listHotelRequest = new ListHotelRequest();
        $listHotelRequest->setRating(2);
        $result = $listHotelRequest->getRating();
        $this->assertEquals(2, $result);
    }

    public function testGetCheckIn()
    {
        $createBookingRequest = new ListHotelRequest();
        $createBookingRequest->setCheckIn(1657182697);
        $result = $createBookingRequest->getCheckIn();
        $expected = $this->timestampToDateTime(1657182697);
        $this->assertEquals($expected, $result);
    }

    public function testGetCheckout()
    {
        $createBookingRequest = new ListHotelRequest();
        $createBookingRequest->setCheckout(1657182697);
        $result = $createBookingRequest->getCheckout();
        $expected = $this->timestampToDateTime(1657182697);
        $this->assertEquals($expected, $result);
    }
}
