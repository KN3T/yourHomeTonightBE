<?php

namespace App\Tests\Service;

use App\Entity\Hotel;
use App\Entity\Room;
use App\Entity\RoomImage;
use App\Mapping\CreateRoomRequestMapper;
use App\Mapping\CreateRoomRequestRoomImageMapper;
use App\Mapping\PutRoomRequestRoomImagesMapper;
use App\Mapping\PutRoomRequestRoomMapper;
use App\Repository\BookingRepository;
use App\Repository\RoomImageRepository;
use App\Repository\RoomRepository;
use App\Request\Room\CreateRoomRequest;
use App\Request\Room\ListRoomRequest;
use App\Request\Room\PutRoomRequest;
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

    public function testFindAll()
    {
        $createRoomRequestMapper = $this->getMockBuilder(CreateRoomRequestMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $roomRepositoryMock = $this->getMockBuilder(RoomRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $putRoomRequestRoomMapperMock = $this->getMockBuilder(PutRoomRequestRoomMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $bookingRepositoryMock = $this->getMockBuilder(BookingRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $roomServiceTest = new RoomService($roomRepositoryMock, $createRoomRequestMapper, $putRoomRequestRoomMapperMock, $bookingRepositoryMock);
        $listRoomRequestTest = new ListRoomRequest();
        $hotel = new Hotel();
        $arrayTest = [new Room()];
        $roomRepositoryMock->expects($this->once())
            ->method('list')
            ->willReturn($arrayTest);
        $this->assertEquals($arrayTest, $roomServiceTest->findAll($hotel, $listRoomRequestTest));
    }

    public function testPut()
    {
        $putRoomRequestRoomImagesMapperMock = $this->getMockBuilder(PutRoomRequestRoomImagesMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $roomImageRepositoryMock = $this->getMockBuilder(RoomImageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $putRoomRequestRoomMapperMock = new PutRoomRequestRoomMapper($putRoomRequestRoomImagesMapperMock, $roomImageRepositoryMock);
        $createRoomRequestMapper = $this->getMockBuilder(CreateRoomRequestMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $roomRepositoryMock = $this->getMockBuilder(RoomRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $bookingRepositoryMock = $this->getMockBuilder(BookingRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $roomServiceTest = new RoomService($roomRepositoryMock, $createRoomRequestMapper, $putRoomRequestRoomMapperMock, $bookingRepositoryMock);
        $room = new Room();
        $room->setNumber(1);
        $room->setType('type');
        $room->setPrice(1.0);
        $room->setAdults(1);
        $room->setChildren(1);
        $room->setAsset([]);
        $room->setBeds(1);
        $room->setDescription('description');
        $room->addRoomImage(new RoomImage());

        $requestTest = new PutRoomRequest();
        $requestTest->setNumber(1);
        $requestTest->setType('type');
        $requestTest->setPrice(1.0);
        $requestTest->setAdults(1);
        $requestTest->setChildren(1);
        $requestTest->setAsset([]);
        $requestTest->setBeds(1);
        $requestTest->setDescription('description');
        $requestTest->setImages([]);

        $roomRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturn($room);
        $putRoomRequestRoomImagesMapperMock->expects($this->once())
            ->method('mapping')
            ->willReturn([new RoomImage()]);
        $roomImageRepositoryMock->expects($this->once())
            ->method('save')
            ->willReturn(new Room());

        $result = $roomServiceTest->put($requestTest, $room);
        $this->assertInstanceOf(Room::class, $result);
    }
}
