<?php

namespace App\Tests\Entity;

use App\Entity\HotelImage;
use App\Entity\Image;
use PHPUnit\Framework\TestCase;

class HotelImageTest extends TestCase
{
    public function testHotelImage()
    {
        $image = new Image();
        $hotelImage = new HotelImage();
        $hotelImage->setImage($image);
        $this->assertEquals($hotelImage->getImage(), $image);
        $this->assertEquals($hotelImage->getId(), null);
    }
}
