<?php

namespace App\Service;

use App\Entity\Hotel;
use App\Repository\HotelRepository;
use App\Request\CreateHotelRequest;

class HotelService
{
    private HotelRepository $hotelRepository;

    public function __construct(HotelRepository $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    public function create(CreateHotelRequest $createHotelRequest): Hotel
    {
        $hotel = new Hotel();

        return $hotel;
    }

}
