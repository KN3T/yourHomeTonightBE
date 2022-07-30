<?php

namespace App\Request\Profile;

use App\Request\BaseRequest;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[Assert\Callback([ChangePasswordRequest::class, 'validate'])]
class ChangePasswordRequest extends BaseRequest
{
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[SecurityAssert\UserPassword(
        message: 'Wrong value for your current password',
    )]
    private $currentPassword;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $newPassword;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private $confirmPassword;

    /**
     * @return mixed
     */
    public function getCurrentPassword()
    {
        return $this->currentPassword;
    }

    /**
     * @param mixed $currentPassword
     */
    public function setCurrentPassword($currentPassword): void
    {
        $this->currentPassword = $currentPassword;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
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

    public static function validate(ChangePasswordRequest $changePasswordRequest, ExecutionContextInterface $context)
    {
        if (strcmp($changePasswordRequest->getNewPassword(), $changePasswordRequest->getConfirmPassword())) {
            $context->buildViolation('Confirm password does not match!')
                ->atPath('confirmPassword')
                ->addViolation();
        }
        if ($changePasswordRequest->getCurrentPassword() === $changePasswordRequest->getNewPassword()) {
            $context->buildViolation('New password must be different from current')
                ->atPath('newPassword')
                ->addViolation();
        }
    }
}
