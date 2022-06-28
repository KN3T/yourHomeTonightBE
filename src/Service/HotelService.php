<?php

namespace App\Service;

use App\Entity\Hotel;
use App\Repository\HotelRepository;
use App\Request\Hotel\ListHotelRequest;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class HotelService
{
    private HotelRepository $hotelRepository;

    public function __construct(HotelRepository $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    public function findAll(ListHotelRequest $hotelRequest): array
    {
        return $this->hotelRepository->list($hotelRequest);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function detail(Hotel $hotel): array
    {
        return $this->hotelRepository->detail($hotel);
    }
}
