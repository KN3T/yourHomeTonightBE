<?php

namespace App\Request\City;

use App\Request\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class ListCityRequest extends BaseRequest
{
    public const DEFAULT_LIMIT = 10;
    public const DEFAULT_OFFSET = 0;

    #[Assert\Type('string')]
    private $search = null;

    #[Assert\Type('integer')]
    private int $limit = self::DEFAULT_LIMIT;

    #[Assert\Type('integer')]
    private int $offset = self::DEFAULT_OFFSET;

    /**
     * @return null
     */
    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search): void
    {
        $this->search = $search;
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
}
