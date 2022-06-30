<?php

namespace App\Request\Room;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class ListRoomRequest extends BaseRequest
{
    public const ORDER_BY_LIST = ['asc', 'desc'];
    public const DEFAULT_SORT_BY = 'price';
    public const DEFAULT_ORDER = 'asc';
    public const DEFAULT_LIMIT = 10;
    public const DEFAULT_OFFSET = 0;

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

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return null
     */
    public function getBeds()
    {
        return $this->beds;
    }

    /**
     * @param null $beds
     */
    public function setBeds($beds): void
    {
        $this->beds = $beds;
    }

    /**
     * @return string
     */
    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    /**
     * @param string $sortBy
     */
    public function setSortBy(string $sortBy): void
    {
        $this->sortBy = $sortBy;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @param string $order
     */
    public function setOrder(string $order): void
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return float|null
     */
    public function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    /**
     * @param float|null $maxPrice
     */
    public function setMaxPrice(?float $maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    /**
     * @return float|null
     */
    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    /**
     * @param float|null $minPrice
     */
    public function setMinPrice(?float $minPrice): void
    {
        $this->minPrice = $minPrice;
    }
}
