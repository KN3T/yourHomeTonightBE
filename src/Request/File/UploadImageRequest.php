<?php

namespace App\Request\File;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class UploadImageRequest extends BaseRequest
{
    #[Assert\All([
        new Assert\Image(
            maxSize: '5M',
            mimeTypes: [
                'image/*',
            ],
            mimeTypesMessage: 'The type of the file is invalid ({{ type }}). Allowed types are {{ types }}.'
        ),
        new Assert\NotBlank(),
    ])]

    private $images = [];

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $image
     */
    public function addImage($image): void
    {
        $this->images[] = $image;
    }
}
