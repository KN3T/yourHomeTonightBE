<?php

namespace App\Tests\Request\Rating;

use App\Request\Rating\PutRatingRequest;
use PHPUnit\Framework\TestCase;

class PutRatingRequestTest extends TestCase
{
    public function testGetContent()
    {
        $putRatingRequest = new PutRatingRequest();
        $putRatingRequest->setContent('That great');
        $result = $putRatingRequest->getContent();
        $this->assertEquals('That great', $result);
    }

    public function testGetRating()
    {
        $putRatingRequest = new PutRatingRequest();
        $putRatingRequest->setRating(5);
        $result = $putRatingRequest->getRating();
        $this->assertEquals(5, $result);
    }
}
