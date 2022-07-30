<?php

namespace App\Tests\Entity;

use App\Entity\Rating;
use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{
    public function testRating()
    {
        $rating = new Rating();
        $date = new \DateTimeImmutable('now');
        $rating->setRating(5);
        $rating->setCreatedAt($date);
        $rating->setUpdatedAt($date);
        $rating->setDeletedAt($date);
        $rating->setContent('content');
        $this->assertEquals($rating->getRating(), 5);
        $this->assertEquals($rating->getCreatedAt(), $date);
        $this->assertEquals($rating->getUpdatedAt(), $date);
        $this->assertEquals($rating->getId(), null);
        $this->assertEquals($rating->getDeletedAt(), $date);
        $this->assertEquals($rating->getContent(), 'content');

    }
}
