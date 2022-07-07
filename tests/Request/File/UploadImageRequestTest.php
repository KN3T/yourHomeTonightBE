<?php

namespace App\Tests\Request\File;

use App\Request\File\UploadImageRequest;
use PHPUnit\Framework\TestCase;

class UploadImageRequestTest extends TestCase
{
    public function testUploadImageRequest()
    {
        $request = new UploadImageRequest();
        $request->addImage('Image 1');

        $this->assertEquals(['Image 1'], $request->getImages());
    }
}
