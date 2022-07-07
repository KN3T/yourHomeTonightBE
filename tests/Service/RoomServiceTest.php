<?php

namespace App\Tests\Service;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Entity\RoomImage;
use App\Mapping\CreateRoomRequestMapper;
use App\Mapping\CreateRoomRequestRoomImageMapper;
use App\Mapping\PutRoomRequestRoomMapper;
use App\Repository\BookingRepository;
use App\Repository\RoomImageRepository;
use App\Repository\RoomRepository;
use App\Request\Room\CreateRoomRequest;
use App\Service\RoomService;
use PHPUnit\Framework\TestCase;

class RoomServiceTest extends TestCase
{
    public function testCreate()
    {
        $createRoomRequestRoomImageMapperMock = $this->getMockBuilder(CreateRoomRequestRoomImageMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $createRoomRequestRoomImageMapperMock->expects($this->once())
            ->method('mapping')
            ->willReturn([new RoomImage()]);
        $roomImageRepositoryMock = $this->getMockBuilder(RoomImageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $roomImageRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturn(new Room());

        $createRoomRequestMapper = new CreateRoomRequestMapper($createRoomRequestRoomImageMapperMock, $roomImageRepositoryMock);
        $roomRepositoryMock = $this->getMockBuilder(RoomRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $putRoomRequestRoomMapperMock = $this->getMockBuilder(PutRoomRequestRoomMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $bookingRepositoryMock = $this->getMockBuilder(BookingRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $roomService = new RoomService($roomRepositoryMock, $createRoomRequestMapper, $putRoomRequestRoomMapperMock, $bookingRepositoryMock);
        $hotelTest = new Hotel();
        $requestTest = new CreateRoomRequest();
        $requestTest->setNumber(1);
        $requestTest->setType('type');
        $requestTest->setPrice(1.0);
        $requestTest->setAdults(1);
        $requestTest->setChildren(1);
        $requestTest->setAsset([]);
        $requestTest->setBeds(1);
        $requestTest->setDescription('description');
        $requestTest->setImages([]);

        $room = $roomService->create($requestTest, $hotelTest);
        $this->assertInstanceOf(Room::class, $room);
    }
}
