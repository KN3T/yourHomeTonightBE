<?php

namespace App\Service;

use App\Entity\Address;
use App\Entity\Hotel;
use App\Entity\User;
use App\Mapping\CreateHotelRequestToAddress;
use App\Mapping\CreateHotelRequestToHotel;
use App\Repository\AddressRepository;
use App\Repository\HotelRepository;
use App\Request\Hotel\CreateHotelRequest;
use Symfony\Component\Security\Core\Security;

class HotelService
{
    private HotelRepository $hotelRepository;
    private AddressRepository $addressRepository;
    private CreateHotelRequestToHotel $createHotelRequestToHotel;
    private CreateHotelRequestToAddress $createHotelRequestToAddress;

    public function __construct(
        HotelRepository             $hotelRepository,
        AddressRepository           $addressRepository,
        CreateHotelRequestToHotel   $createHotelRequestToHotel,
        CreateHotelRequestToAddress $createHotelRequestToAddress,
    )
    {
        $this->hotelRepository = $hotelRepository;
        $this->addressRepository = $addressRepository;
        $this->createHotelRequestToHotel = $createHotelRequestToHotel;
        $this->createHotelRequestToAddress = $createHotelRequestToAddress;

    }

    public function create(CreateHotelRequest $createHotelRequest, User $currentUser): Hotel
    {
        $hotel = new Hotel();
        $address = new Address();
        $this->createHotelRequestToAddress->mapping($createHotelRequest, $address);
        $this->createHotelRequestToHotel->mapping($createHotelRequest, $currentUser, $address, $hotel);
        $this->addressRepository->save($address);
        $this->hotelRepository->save($hotel);
        return $hotel;
    }

    public function checkCreatedHotel(User $user): bool
    {
        if ($this->hotelRepository->findOneBy(['user' => $user]) != null) {
            return true;
        }
        return false;
    }
}
