<?php

namespace App\Tests\Transformer;

use App\Transformer\HotelRevenueTransformer;
use PHPUnit\Framework\TestCase;

class HotelRevenueTransformerTest extends TestCase
{
    public function testToArray()
    {
        $hotelRevenue = [
            'roomId' => 1,
            'roomNumber' => '1',
            'revenue' => 100,
        ];

        $hotelRevenueTransformer = new HotelRevenueTransformer();
        $result = $hotelRevenueTransformer->toArray($hotelRevenue);

        $this->assertEquals($hotelRevenue['roomId'], $result['roomId']);
        $this->assertEquals($hotelRevenue['roomNumber'], $result['roomNumber']);
        $this->assertEquals($hotelRevenue['revenue'], $result['revenue']);
    }

    public function testListToArray()
    {
        $hotelRevenues = [
            [
                'roomId' => 1,
                'roomNumber' => '1',
                'revenue' => 100,
            ],
            [
                'roomId' => 2,
                'roomNumber' => '2',
                'revenue' => 200,
            ],
        ];

        $hotelRevenueTransformer = new HotelRevenueTransformer();
        $result = $hotelRevenueTransformer->listToArray($hotelRevenues);

        $this->assertEquals(2, $result['total']);
        $this->assertEquals($hotelRevenues[0]['roomId'], $result['revenues'][0]['roomId']);
        $this->assertEquals($hotelRevenues[0]['roomNumber'], $result['revenues'][0]['roomNumber']);
        $this->assertEquals($hotelRevenues[0]['revenue'], $result['revenues'][0]['revenue']);
        $this->assertEquals($hotelRevenues[1]['roomId'], $result['revenues'][1]['roomId']);
        $this->assertEquals($hotelRevenues[1]['roomNumber'], $result['revenues'][1]['roomNumber']);
    }
}
