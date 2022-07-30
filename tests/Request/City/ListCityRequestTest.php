<?php

namespace App\Tests\Request\City;

use App\Request\City\ListCityRequest;
use PHPUnit\Framework\TestCase;

class ListCityRequestTest extends TestCase
{
    public function testGetSearch()
    {
        $listCityRequest = new ListCityRequest();
        $listCityRequest->setSearch('Can Tho');
        $result = $listCityRequest->getSearch();
        $this->assertEquals('Can Tho', $result);
    }

    public function testGetLimit()
    {
        $listCityRequest = new ListCityRequest();
        $listCityRequest->setLimit(10);
        $result = $listCityRequest->getLimit();
        $this->assertEquals(10, $result);
    }

    public function testGetOffset()
    {
        $listCityRequest = new ListCityRequest();
        $listCityRequest->setOffset(10);
        $result = $listCityRequest->getOffset();
        $this->assertEquals(10, $result);
    }
}
