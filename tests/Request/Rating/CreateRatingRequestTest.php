<?php

namespace App\Tests\Request\Rating;

use App\Request\Rating\CreateRatingRequest;
use PHPUnit\Framework\TestCase;

class CreateRatingRequestTest extends TestCase
{
    public function testGetContent()
    {
        $createRatingRequest = new CreateRatingRequest();
        $createRatingRequest->setContent('That great');
        $result = $createRatingRequest->getContent();
        $this->assertEquals('That great', $result);
    }

    public function testGetRating()
    {
        $createRatingRequest = new CreateRatingRequest();
        $createRatingRequest->setRating(5);
        $result = $createRatingRequest->getRating();
        $this->assertEquals(5, $result);
    }
}
