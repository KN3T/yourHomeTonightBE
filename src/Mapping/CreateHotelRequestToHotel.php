<?php

namespace App\Mapping;

use App\Entity\Hotel;
use App\Repository\AddressRepository;
use App\Repository\HotelImageRepository;
use App\Request\Hotel\CreateHotelRequest;
use Symfony\Component\Security\Core\Security;

class CreateHotelRequestToHotel
{
    private Security $security;
    private CreateHotelRequestToAddress $createHotelRequestToAddress;
    private CreateHotelRequestToHotelImages $createHotelRequestToHotelImages;
    private AddressRepository $addressRepository;
    private HotelImageRepository $hotelImageRepository;

    public function __construct(
        Security $security,
        CreateHotelRequestToAddress $createHotelRequestToAddress,
        CreateHotelRequestToHotelImages $createHotelRequestToHotelImages,
        AddressRepository $addressRepository,
        HotelImageRepository $hotelImageRepository,
    ) {
        $this->security = $security;
        $this->createHotelRequestToAddress = $createHotelRequestToAddress;
        $this->createHotelRequestToHotelImages = $createHotelRequestToHotelImages;
        $this->addressRepository = $addressRepository;
        $this->hotelImageRepository = $hotelImageRepository;
    }

    public function mapping(CreateHotelRequest $createHotelRequest, Hotel $hotel): Hotel
    {
        $hotel->setUser($this->security->getUser());
        $hotel->setName($createHotelRequest->getName());
        $hotel->setEmail($createHotelRequest->getEmail());
        $hotel->setPhone($createHotelRequest->getPhone());
        $hotel->setRules($createHotelRequest->getRules());
        $hotel->setDescription($createHotelRequest->getDescription());

        $address = $this->createHotelRequestToAddress->mapping($createHotelRequest, $hotel);
        $this->addressRepository->save($address);
        $hotel->setAddress($address);
        $hotelImages = $this->createHotelRequestToHotelImages->mapping($createHotelRequest, $hotel);
        if (!empty($hotelImages)) {
            foreach ($hotelImages as $hotelImage) {
                $this->hotelImageRepository->save($hotelImage);
                $hotel->addHotelImage($hotelImage);
            }
        }
        return $hotel;
    }
}
