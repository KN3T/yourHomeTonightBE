<?php

namespace App\Transformer;

use App\Entity\Image;

class ImageTransformer extends BaseTransformer
{
    const ATTRIBUTES = ['id','path'];
    public function toArray(Image $image): array
    {
        return $this->transform($image, self::ATTRIBUTES);
    }
}
