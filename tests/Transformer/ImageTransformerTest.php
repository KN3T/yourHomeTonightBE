<?php

namespace App\Tests\Transformer;

use App\Entity\Image;
use App\Transformer\ImageTransformer;
use PHPUnit\Framework\TestCase;

class ImageTransformerTest extends TestCase
{
    public function testToArray()
    {
        $image = new Image();
        $image->setPath('path/to/image.png');
        $imageTransformer = new ImageTransformer('');
        $result = $imageTransformer->toArray($image);
        $this->assertEquals([
            'id' => null,
            'src' => 'path/to/image.png'
        ], $result);
    }

    public function testListToArray()
    {
        $image = new Image();
        $image->setPath('path/to/image.png');

        $images = [
            $image,
            $image,
        ];
        $imageTransformer = new ImageTransformer('');
        $result = $imageTransformer->listToArray($images);
        $this->assertCount(2, $result);
    }

}
