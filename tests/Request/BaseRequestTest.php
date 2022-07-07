<?php

namespace App\Tests\Request;

use App\Request\BaseRequest;
use App\Request\City\ListCityRequest;
use PHPUnit\Framework\TestCase;

class BaseRequestTest extends TestCase
{
    public function testGetSetBaseRequest()
    {
        $request = new ListCityRequest();
        $allow = ['search' => 'Can Tho', 'limit' => 1, 'offset' => 1, 'sss' => 1];
        $listCityRequest = $request->fromArray($allow);
        $this->assertEquals('Can Tho', $listCityRequest->getSearch());
        $this->assertEquals(1, $listCityRequest->getLimit());
        $this->assertEquals(1, $listCityRequest->getOffset());
    }
}
