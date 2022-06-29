<?php

namespace App\Request\File;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class UploadImageRequest extends BaseRequest
{
    #[Assert\Image(
        maxSize: '5M',
        mimeTypes: [
            'image/jpeg',
            'image/jpg',
            'image/png'
        ],
        mimeTypesMessage: 'The type of the file is invalid ({{ type }}). Allowed types are {{ types }}.'
    )]
    private $image;


    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }
}