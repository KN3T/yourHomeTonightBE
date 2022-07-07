<?php

namespace App\Tests\Request\Room;

use App\Request\Room\CreateRoomRequest;
use PHPUnit\Framework\TestCase;

class CreateRoomRequestTest extends TestCase
{
    public function testCreateRoomRequest()
    {
        $request = new CreateRoomRequest();
        $request->setNumber(1);
        $request->setType('type');
        $request->setPrice(1.0);
        $request->setAdults(1);
        $request->setChildren(1);
        $request->setAsset([]);
        $request->setBeds(1);
        $request->setDescription('description');
        $request->setImages([]);

        $this->assertEquals(1, $request->getNumber());
        $this->assertEquals('type', $request->getType());
        $this->assertEquals(1.0, $request->getPrice());
        $this->assertEquals(1, $request->getAdults());
        $this->assertEquals(1, $request->getChildren());
        $this->assertEquals([], $request->getAsset());
        $this->assertEquals(1, $request->getBeds());
        $this->assertEquals('description', $request->getDescription());
        $this->assertEquals([], $request->getImages());
    }
}
