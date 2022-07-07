<?php

namespace App\Tests\Transformer;

use App\Entity\Image;
use App\Entity\RoomImage;
use App\Transformer\RoomImageTransformer;
use PHPUnit\Framework\TestCase;

class RoomImageTransformerTest extends TestCase
{
    /**
     * @var RoomImageTransformer
     */
    private $roomImageTransformer;

    protected function setUp(): void
    {
        $this->roomImageTransformer = new RoomImageTransformer();
    }

    public function testTransform(): void
    {
        $image = new Image();
        $image->setPath('/path/to/image');
        $roomImage = new RoomImage();
        $roomImage->setImage($image);

        $roomImageTransformed = $this->roomImageTransformer->toArray($roomImage);
        $expectedArray = [
            "imageId" => null,
            "src" => "/path/to/image"
        ];
        $this->assertEquals($expectedArray, $roomImageTransformed);

        $image2 = new Image();
        $image2->setPath('/path/to/image');
        $roomImage2 = new RoomImage();
        $roomImage2->setImage($image);

        $ListRoomImageTransformed = $this->roomImageTransformer->listToArray([$roomImage, $roomImage2]);

        $expectedArray = [
            [
                "imageId" => null,
                "src" => "/path/to/image"
            ],
            [
                "imageId" => null,
                "src" => "/path/to/image"
            ]
        ];
        $this->assertEquals($expectedArray, $ListRoomImageTransformed);
    }
}
