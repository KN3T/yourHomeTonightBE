<?php

namespace App\Request\Profile;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class PutProfileRequest extends BaseRequest
{
    #[Assert\Type('string')]
    #[Assert\NotBlank]
        private $fullName;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
        private $phone;

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }
}
