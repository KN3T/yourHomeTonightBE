<?php

namespace App\Tests\Service;

use App\Entity\Image;
use App\Manager\FileManager;
use App\Repository\ImageRepository;
use App\Request\File\UploadImageRequest;
use App\Service\ImageService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageServiceTest extends TestCase
{
    public function testUploadSuccess()
    {
        $fileManagerMock = $this->getMockBuilder(FileManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageRepositoryMock = $this->getMockBuilder(ImageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileManagerMock->expects($this->any())
            ->method('upload')
            ->willReturn('http://example.com/image1.jpg');
        $imageService = new ImageService($fileManagerMock, $imageRepositoryMock);
        $uploadImageRequest = $this->getMockBuilder(UploadImageRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uploadFile = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();
        $uploadImageRequest->expects($this->once())
            ->method('getImages')
            ->willReturn([
                $uploadFile,
                $uploadFile
            ]);
        $result = $imageService->upload($uploadImageRequest);
        $this->assertCount(2, $result);
    }

    public function testCheckImageExistSuccess()
    {
        $fileManagerMock = $this->getMockBuilder(FileManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageRepositoryMock = $this->getMockBuilder(ImageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileManagerMock->expects($this->any())
            ->method('upload')
            ->willReturn('http://example.com/image1.jpg');
        $imageService = new ImageService($fileManagerMock, $imageRepositoryMock);
        $image = new Image();
        $imageRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($image);
        $this->assertTrue($imageService->checkImageExist($image));
    }

    public function testCheckImageExistFail()
    {
        $fileManagerMock = $this->getMockBuilder(FileManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageRepositoryMock = $this->getMockBuilder(ImageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileManagerMock->expects($this->any())
            ->method('upload')
            ->willReturn('http://example.com/image1.jpg');
        $imageService = new ImageService($fileManagerMock, $imageRepositoryMock);
        $image = new Image();
        $imageRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn(null);
        $this->assertFalse($imageService->checkImageExist($image));
    }

    public function testDeleteImage()
    {
        $fileManagerMock = $this->getMockBuilder(FileManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $imageRepositoryMock = $this->getMockBuilder(ImageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $fileManagerMock->expects($this->any())
            ->method('upload')
            ->willReturn('http://example.com/image1.jpg');
        $imageService = new ImageService($fileManagerMock, $imageRepositoryMock);
        $image = new Image();
        $imageRepositoryMock->expects($this->once())
            ->method('remove')
            ->willReturn(true);
        $imageService->delete($image);
    }
}
