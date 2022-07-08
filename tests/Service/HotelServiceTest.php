<?php

namespace App\Tests\Service;

use App\Entity\Hotel;
use App\Entity\User;
use App\Mapping\CreateHotelRequestToHotel;
use App\Mapping\PutHotelRequestToHotel;
use App\Repository\HotelRepository;
use App\Request\Hotel\CreateHotelRequest;
use App\Request\Hotel\ListHotelRequest;
use App\Request\Hotel\PutHotelRequest;
use App\Service\HotelService;
use App\Transformer\ListHotelTransformer;
use PHPUnit\Framework\TestCase;

class HotelServiceTest extends TestCase
{
    public function testCheckCreatedHotelSuccess()
    {
        $hotelRepositoryMock = $this->getMockBuilder(HotelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createHotelRequestToHotelMock = $this->getMockBuilder(CreateHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $putHotelRequestToHotelMock = $this->getMockBuilder(PutHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelService = new HotelService(
            $hotelRepositoryMock,
            $createHotelRequestToHotelMock,
            $putHotelRequestToHotelMock
        );

        $user = new User();
        $hotelRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($user);
        $this->assertTrue($hotelService->checkCreatedHotel($user));
    }

    public function testCheckCreatedHotelFail()
    {
        $hotelRepositoryMock = $this->getMockBuilder(HotelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createHotelRequestToHotelMock = $this->getMockBuilder(CreateHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $putHotelRequestToHotelMock = $this->getMockBuilder(PutHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelService = new HotelService(
            $hotelRepositoryMock,
            $createHotelRequestToHotelMock,
            $putHotelRequestToHotelMock
        );

        $user = new User();
        $hotelRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);
        $this->assertFalse($hotelService->checkCreatedHotel($user));
    }

    public function testCheckHotelOwner()
    {
        $hotelRepositoryMock = $this->getMockBuilder(HotelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createHotelRequestToHotelMock = $this->getMockBuilder(CreateHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $putHotelRequestToHotelMock = $this->getMockBuilder(PutHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelService = new HotelService(
            $hotelRepositoryMock,
            $createHotelRequestToHotelMock,
            $putHotelRequestToHotelMock
        );

        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $hotel = new Hotel();
        $hotel->setUser($user);
        $this->assertTrue($hotelService->checkHotelOwner($hotel, $user));
    }

    public function testFindAll()
    {
        $hotelRepositoryMock = $this->getMockBuilder(HotelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createHotelRequestToHotelMock = $this->getMockBuilder(CreateHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $putHotelRequestToHotelMock = $this->getMockBuilder(PutHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelService = new HotelService(
            $hotelRepositoryMock,
            $createHotelRequestToHotelMock,
            $putHotelRequestToHotelMock
        );
        $hotelRepositoryMock->expects($this->once())
            ->method('list')
            ->willReturn([]);

        $listHotelRequest = $this->getMockBuilder(ListHotelRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->assertEquals([], $hotelService->findAll($listHotelRequest));
    }

    public function testDetailHotel()
    {
        $hotelRepositoryMock = $this->getMockBuilder(HotelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createHotelRequestToHotelMock = $this->getMockBuilder(CreateHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $putHotelRequestToHotelMock = $this->getMockBuilder(PutHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelService = new HotelService(
            $hotelRepositoryMock,
            $createHotelRequestToHotelMock,
            $putHotelRequestToHotelMock
        );
        $hotelRepositoryMock->expects($this->once())
            ->method('detail')
            ->willReturn([]);
        $hotel = new Hotel();
        $this->assertEquals([], $hotelService->detail($hotel));
    }

    public function testCreateHotel()
    {
        $hotelRepositoryMock = $this->getMockBuilder(HotelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createHotelRequestToHotelMock = $this->getMockBuilder(CreateHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $putHotelRequestToHotelMock = $this->getMockBuilder(PutHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelService = new HotelService(
            $hotelRepositoryMock,
            $createHotelRequestToHotelMock,
            $putHotelRequestToHotelMock
        );

        $createHotelRequest = new CreateHotelRequest();

        $this->assertInstanceOf(Hotel::class, $hotelService->create($createHotelRequest));

    }

    public function testPutHotel()
    {
        $hotelRepositoryMock = $this->getMockBuilder(HotelRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createHotelRequestToHotelMock = $this->getMockBuilder(CreateHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $putHotelRequestToHotelMock = $this->getMockBuilder(PutHotelRequestToHotel::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hotelService = new HotelService(
            $hotelRepositoryMock,
            $createHotelRequestToHotelMock,
            $putHotelRequestToHotelMock
        );

        $putHotelRequest = new PutHotelRequest();
        $hotel = new Hotel();

        $this->assertInstanceOf(Hotel::class, $hotelService->put($putHotelRequest, $hotel));

    }
}
