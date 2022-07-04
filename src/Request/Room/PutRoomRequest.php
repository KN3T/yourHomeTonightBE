<?php

namespace App\Request\Room;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class PutRoomRequest extends BaseRequest
{
    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    private $number;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $type;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    private $price;

    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    private $adults;

    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    private $children;

    #[Assert\Type('array')]
    #[Assert\NotBlank]
    private $asset;

    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    private $beds;

    #[Assert\Type('string')]
    private $description;

    #[Assert\All(
        new Assert\Type('numeric')
    )]
    #[Assert\NotBlank]
    private $images = [];

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getAdults()
    {
        return $this->adults;
    }

    /**
     * @param mixed $adults
     */
    public function setAdults($adults): void
    {
        $this->adults = $adults;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getAsset()
    {
        return $this->asset;
    }

    /**
     * @param mixed $asset
     */
    public function setAsset($asset): void
    {
        $this->asset = $asset;
    }

    /**
     * @return mixed
     */
    public function getBeds()
    {
        return $this->beds;
    }

    /**
     * @param mixed $beds
     */
    public function setBeds($beds): void
    {
        $this->beds = $beds;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }
}
