<?php

namespace App\Tests\Transformer;

use App\Entity\HotelImage;
use App\Entity\Image;
use App\Transformer\HotelImageTransformer;
use PHPUnit\Framework\TestCase;

class HotelImageTransformerTest extends TestCase
{
    public function testToArray()
    {
        $image = new Image();
        $image->setPath('/path/to/image');
        $hotelImage = new HotelImage();
        $hotelImage->setImage($image);
        $hotelImageTransformer = new HotelImageTransformer();
        $result = $hotelImageTransformer->toArray($hotelImage);
        $this->assertEquals(['imageId' => null, 'src' => '/path/to/image'], $result);
    }

    public function testListToArray()
    {
        $image = new Image();
        $image->setPath('/path/to/image');
        $hotelImage = new HotelImage();
        $hotelImage->setImage($image);
        $hotelImageTransformer = new HotelImageTransformer();
        $result = $hotelImageTransformer->listToArray([$hotelImage]);
        $this->assertEquals([['imageId' => null, 'src' => '/path/to/image']], $result);
    }
}
