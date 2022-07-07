<?php

namespace App\Tests\Request\Room;

use App\Request\Room\ListRoomRequest;
use App\Traits\DateTimeTraits;
use PHPUnit\Framework\TestCase;

class ListRoomRequestTest extends TestCase
{
    use DateTimeTraits;

    public function testListRoomRequest()
    {
        $request = new ListRoomRequest();
        $request->setType('GOLD');
        $request->setOffset(1);
        $request->setLimit(1);
        $request->setBeds(1);
        $request->setSortBy('asc');
        $request->setOrder('price');
        $request->setMaxPrice(200.0);
        $request->setMinPrice(200.0);
        $request->setAdults(3);
        $request->setChildren(3);
        $request->setRating(3);
        $request->setCheckIn(1657182697);
        $request->setCheckOut(1657182697);
        
        $expectedDate = $this->timestampToDateTime(1657182697);


        $this->assertEquals(1, $request->getOffset());
        $this->assertEquals(1, $request->getLimit());
        $this->assertEquals(1, $request->getBeds());
        $this->assertEquals('GOLD', $request->getType());
        $this->assertEquals('asc', $request->getSortBy());
        $this->assertEquals('price', $request->getOrder());
        $this->assertEquals(200.0, $request->getMaxPrice());
        $this->assertEquals(200.0, $request->getMinPrice());
        $this->assertEquals(3, $request->getAdults());
        $this->assertEquals(3, $request->getChildren());
        $this->assertEquals(3, $request->getRating());
        $this->assertEquals($expectedDate, $request->getCheckIn());
        $this->assertEquals($expectedDate, $request->getCheckOut());
    }
}
