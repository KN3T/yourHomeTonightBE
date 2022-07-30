<?php

namespace App\Request\Hotel;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class PutHotelRequest extends BaseRequest
{
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $name;

    #[Assert\Type('string')]
    private $email;

    #[Assert\Type('string')]
    private $phone;

    #[Assert\Type('string')]
    private $city;

    #[Assert\Type('string')]
    private $province;

    #[Assert\Type('string')]
    private $address;

    #[Assert\Type('string')]
    private $description;

    #[Assert\Type('array')]
    private $rules;

    #[Assert\Type('array')]
    private $images = [];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     */
    public function setProvince($province): void
    {
        $this->province = $province;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
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

    /**
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param mixed $rules
     */
    public function setRules($rules): void
    {
        $this->rules = $rules ?? [];
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

    public function setImages(array $images): void
    {
        $this->images = $images;
    }
}
