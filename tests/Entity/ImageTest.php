<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testImage()
    {
        $image = new Image();
        $date = new \DateTimeImmutable('now');
        $image->setPath('/path/to/image');
        $image->setCreatedAt($date);
        $image->setDeletedAt($date);
        $this->assertEquals($image->getPath(), '/path/to/image');
        $this->assertEquals($image->getCreatedAt(), $date);
        $this->assertEquals($image->getDeletedAt(), $date);
        $this->assertEquals($image->getId(), null);
    }
}
