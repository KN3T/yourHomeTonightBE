<?php

namespace App\Service;

use App\Entity\Image;
use App\Manager\FileManager;
use App\Repository\ImageRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    private FileManager $fileManager;
    private ImageRepository $imageRepository;

    public function __construct(FileManager $fileManager, ImageRepository $imageRepository)
    {
        $this->fileManager =$fileManager;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function upload(UploadedFile $file): Image
    {
        $image = new Image();
        $imageURL = $this->fileManager->upload($file);
        $image->setPath($imageURL);
        $this->imageRepository->save($image);
        return $image;
    }
}
