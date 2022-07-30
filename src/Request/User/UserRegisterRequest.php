<?php

namespace App\Request\User;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[Assert\Callback([UserRegisterRequest::class, 'validate'])]
class UserRegisterRequest extends BaseRequest
{
    #[Assert\Email]
    #[Assert\NotBlank]
    private $email;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $password;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $confirmPassword;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $fullName;

    #[Assert\Type('boolean')]
    private $isHotel;

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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * @param mixed $confirmPassword
     */
    public function setConfirmPassword($confirmPassword): void
    {
        $this->confirmPassword = $confirmPassword;
    }

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
    public function getIsHotel()
    {
        return $this->isHotel;
    }

    /**
     * @param mixed $isHotel
     */
    public function setIsHotel($isHotel): void
    {
        $this->isHotel = $isHotel;
    }

    public static function validate(UserRegisterRequest $userRegisterRequest, ExecutionContextInterface $context)
    {
        if (strcmp($userRegisterRequest->getPassword(), $userRegisterRequest->getConfirmPassword())) {
            $context->buildViolation('Confirm password does not match!')
                ->atPath('confirmPassword')
                ->addViolation();
        }
    }
}
