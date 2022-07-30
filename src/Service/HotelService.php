<?php

namespace App\Service;

use App\Entity\Hotel;
use App\Entity\User;
use App\Mapping\CreateHotelRequestToHotel;
use App\Mapping\PutHotelRequestToHotel;
use App\Repository\HotelRepository;
use App\Request\Hotel\CreateHotelRequest;
use App\Request\Hotel\ListHotelRequest;
use App\Request\Hotel\PutHotelRequest;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

class HotelService
{
    private HotelRepository $hotelRepository;
    private CreateHotelRequestToHotel $createHotelRequestToHotel;
    private PutHotelRequestToHotel $putHotelRequestToHotel;

    public function __construct(
        HotelRepository $hotelRepository,
        CreateHotelRequestToHotel $createHotelRequestToHotel,
        PutHotelRequestToHotel $putHotelRequestToHotel,
    ) {
        $this->hotelRepository = $hotelRepository;
        $this->createHotelRequestToHotel = $createHotelRequestToHotel;
        $this->putHotelRequestToHotel = $putHotelRequestToHotel;
    }

    public function create(CreateHotelRequest $createHotelRequest): Hotel
    {
        $hotel = new Hotel();
        $this->createHotelRequestToHotel->mapping($createHotelRequest, $hotel);
        $this->hotelRepository->save($hotel);

        return $hotel;
    }

    public function put(PutHotelRequest $putHotelRequest, Hotel $hotel): Hotel
    {
        $this->putHotelRequestToHotel->mapping($putHotelRequest, $hotel);
        $this->hotelRepository->save($hotel);

        return $hotel;
    }

    public function checkCreatedHotel(User $user): bool
    {
        if (null != $this->hotelRepository->findOneBy(['user' => $user])) {
            return true;
        }

        return false;
    }

    public function checkHotelOwner(Hotel $hotel, User $user): bool
    {
        return $hotel->getUser() === $user || $user->isAdmin();
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
