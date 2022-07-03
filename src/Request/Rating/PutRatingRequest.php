<?php

namespace App\Request\Rating;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class PutRatingRequest extends BaseRequest
{
    #[Assert\Type('string')]
    #[Assert\NotNull]
    private $content;

    #[Assert\Type('int')]
    #[Assert\Choice([1, 2, 3, 4, 5], message: 'rating must be from 1 to 5')]
    #[Assert\NotBlank]
    private $rating;

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating): void
    {
        $this->rating = $rating;
    }
}
