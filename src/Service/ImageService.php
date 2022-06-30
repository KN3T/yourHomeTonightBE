<?php

namespace App\Service;

use App\Entity\Image;
use App\Manager\FileManager;
use App\Mapping\DeleteImageRequestToImage;
use App\Repository\ImageRepository;
use App\Request\File\UploadImageRequest;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImageService
{
    private FileManager $fileManager;
    private ImageRepository $imageRepository;
    private DeleteImageRequestToImage $deleteImageRequestToImage;

    public function __construct(
        FileManager $fileManager,
        ImageRepository $imageRepository,
        DeleteImageRequestToImage $deleteImageRequestToImage,
    ) {
        $this->fileManager = $fileManager;
        $this->imageRepository = $imageRepository;
        $this->deleteImageRequestToImage = $deleteImageRequestToImage;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function upload(UploadImageRequest $uploadImageRequest): array
    {
        $result = [];
        foreach ($uploadImageRequest->getImages() as $file) {
            $image = new Image();
            $imageURL = $this->fileManager->upload($file);
            $image->setPath($imageURL);
            $this->imageRepository->save($image);
            $result[] = $image;
        }

        return $result;
    }

    public function delete(Image $image): void
    {
        $this->imageRepository->remove($image);
    }

    public function checkImageExist(Image $image)
    {
        if (null != $this->imageRepository->find($image->getId())) {
            return true;
        }

        return false;
    }
}
