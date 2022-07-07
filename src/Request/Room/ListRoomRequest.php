<?php

namespace App\Request\Room;

use App\Request\BaseRequest;
use App\Traits\DateTimeTraits;
use Symfony\Component\Validator\Constraints as Assert;

class ListRoomRequest extends BaseRequest
{
    use DateTimeTraits;

    public const ORDER_BY_LIST = ['asc', 'desc'];
    public const DEFAULT_SORT_BY = 'price';
    public const DEFAULT_ORDER = 'asc';
    public const DEFAULT_LIMIT = 10;
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_ADULTS = 1;
    public const DEFAULT_CHILDREN = 1;

    #[Assert\Type('string')]
    private $type = null;

    #[Assert\Type('numeric')]
    private $beds = null;

    #[Assert\Type('string')]
    private $sortBy = self::DEFAULT_SORT_BY;

    #[Assert\Choice(
        choices: self::ORDER_BY_LIST,
    )]
    #[Assert\Type('string')]
    private $order = self::DEFAULT_ORDER;

    #[Assert\Type('integer')]
    private int $limit = self::DEFAULT_LIMIT;

    #[Assert\Type('integer')]
    private int $offset = self::DEFAULT_OFFSET;

    #[Assert\Type('numeric')]
    private ?float $maxPrice = null;

    #[Assert\Type('numeric')]
    private ?float $minPrice = null;

    #[Assert\Type('numeric')]
    private ?int $checkIn = null;

    #[Assert\Type('numeric')]
    private ?int $checkOut = null;

    #[Assert\Type('int')]
    private ?int $adults = self::DEFAULT_ADULTS;

    #[Assert\Type('int')]
    private ?int $children = self::DEFAULT_CHILDREN;

    #[Assert\Type('numeric')]
    private $rating = null;

    public function __construct()
    {
        $now = new \DateTimeImmutable('now');
        $this->checkIn = $this->datetime2Timestamp($now);
        $future = $now->modify('+3 day');
        $this->checkOut = $this->datetime2Timestamp($future);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }


    public function getBeds()
    {
        return $this->beds;
    }

    public function setBeds($beds): void
    {
        $this->beds = $beds;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function setSortBy(string $sortBy): void
    {
        $this->sortBy = $sortBy;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function setOrder(string $order): void
    {
        $this->order = $order;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    public function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?float $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    public function setMinPrice(?float $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    public function getCheckIn(): ?\DateTime
    {
        return $this->timestampToDateTime($this->checkIn);
    }

    public function setCheckIn(?int $checkIn): void
    {
        $this->checkIn = $checkIn;
    }

    public function getCheckOut(): ?\DateTime
    {
        return $this->timestampToDateTime($this->checkOut);
    }

    public function setCheckOut(?int $checkOut): void
    {
        $this->checkOut = $checkOut;
    }

    public function getAdults(): ?int
    {
        return $this->adults;
    }

    public function setAdults(?int $adults): void
    {
        $this->adults = $adults;
    }

    public function getChildren(): ?int
    {
        return $this->children;
    }

    public function setChildren(?int $children): void
    {
        $this->children = $children;
    }

    public function getRating()
    {
        return $this->rating;
    }


    public function setRating($rating): void
    {
        $this->rating = $rating;
    }
}
