<?php

namespace App\Request\Booking;

use App\Request\BaseRequest;
use App\Traits\DateTimeTraits;
use Symfony\Component\Validator\Constraints as Assert;

class CreateBookingRequest extends BaseRequest
{
    use DateTimeTraits;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $fullName;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $email;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $phone;

    #[Assert\Type('int')]
    #[Assert\NotBlank]
    private $checkIn;

    #[Assert\Type('int')]
    #[Assert\NotBlank]
    private $checkOut;

    #[Assert\Type('int')]
    #[Assert\NotBlank]
    private $userId;

    #[Assert\Type('int')]
    #[Assert\NotBlank]
    private $roomId;

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
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
    public function getCheckIn()
    {
        return $this->timestampToDateTime($this->checkIn);
    }

    /**
     * @param mixed $checkIn
     */
    public function setCheckIn($checkIn): void
    {
        $this->checkIn = $checkIn;
    }

    /**
     * @return mixed
     */
    public function getCheckOut()
    {
        return $this->timestampToDateTime($this->checkOut);
    }

    /**
     * @param mixed $checkOut
     */
    public function setCheckOut($checkOut): void
    {
        $this->checkOut = $checkOut;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * @param mixed $roomId
     */
    public function setRoomId($roomId): void
    {
        $this->roomId = $roomId;
    }
}
