<?php

namespace App\Service;

use App\Entity\Hotel;
use App\Repository\HotelRepository;
use App\Request\Hotel\ListHotelRequest;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use App\Entity\Address;
use App\Entity\User;
use App\Mapping\CreateHotelRequestToAddress;
use App\Mapping\CreateHotelRequestToHotel;
use App\Mapping\CreateHotelRequestToHotelImages;
use App\Mapping\PutHotelRequestToAddress;
use App\Mapping\PutHotelRequestToHotel;
use App\Repository\AddressRepository;
use App\Request\Hotel\CreateHotelRequest;
use App\Request\Hotel\PutHotelRequest;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class HotelService
{
    private HotelRepository $hotelRepository;
    private AddressRepository $addressRepository;
    private CreateHotelRequestToHotel $createHotelRequestToHotel;
    private CreateHotelRequestToAddress $createHotelRequestToAddress;
    private CreateHotelRequestToHotelImages $createHotelRequestToHotelImages;
    private PutHotelRequestToHotel      $putHotelRequestToHotel;
    private PutHotelRequestToAddress    $putHotelRequestToAddress;

    public function __construct(
        HotelRepository             $hotelRepository,
        AddressRepository           $addressRepository,
        CreateHotelRequestToHotel   $createHotelRequestToHotel,
        CreateHotelRequestToAddress $createHotelRequestToAddress,
        CreateHotelRequestToHotelImages $createHotelRequestToHotelImages,
        PutHotelRequestToHotel      $putHotelRequestToHotel,
        PutHotelRequestToAddress    $putHotelRequestToAddress,
    )
    {
        $this->hotelRepository = $hotelRepository;
        $this->addressRepository = $addressRepository;
        $this->createHotelRequestToHotel = $createHotelRequestToHotel;
        $this->createHotelRequestToAddress = $createHotelRequestToAddress;
        $this->createHotelRequestToHotelImages = $createHotelRequestToHotelImages;
        $this->putHotelRequestToAddress = $putHotelRequestToAddress;
        $this->putHotelRequestToHotel = $putHotelRequestToHotel;

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
    public function create(CreateHotelRequest $createHotelRequest, User $currentUser): Hotel
    {
        $hotel = new Hotel();
        $this->createHotelRequestToHotel->mapping($createHotelRequest, $hotel);
        $this->hotelRepository->save($hotel);
        return $hotel;
    }
    public function put(PutHotelRequest $putHotelRequest, Hotel $hotel): Hotel
    {
        $address = $hotel->getAddress();
        $this->putHotelRequestToAddress->mapping($putHotelRequest, $address);
        $this->putHotelRequestToHotel->mapping($putHotelRequest, $hotel);
        $date = new DateTime('now');
        $address->setUpdatedAt($date);
        $hotel->setUpdatedAt($date);
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

    public function checkHotelOwner(Hotel $hotel, User $user): bool
    {
        if ($this->hotelRepository->findOneBy(['id' => $hotel->getId(), 'user' => $user])) {
            return true;
        }
        return false;
    }
}
