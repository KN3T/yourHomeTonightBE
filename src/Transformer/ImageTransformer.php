<?php

namespace App\Transformer;

use App\Entity\Image;

class ImageTransformer extends BaseTransformer
{
    public const ATTRIBUTES = ['id'];
    private string $s3Url;

    public function __construct($s3Url)
    {
        $this->s3Url = $s3Url;
    }

    public function toArray(Image $image): array
    {
        $result = $this->transform($image, self::ATTRIBUTES);
        $result['src'] = $image->getPath();

        return $result;
    }

    public function listToArray(array $images): array
    {
        $result = [];
        if (!empty($images)) {
            foreach ($images as $image) {
                $result[] = $this->toArray($image);
            }
        }

        return $result;
    }
}
