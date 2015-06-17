<?php

namespace DvsaClientTest\Mapper;

use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommonTest\TestUtils\XMock;

abstract class AbstractMapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var $client Client|\PHPUnit_Framework_MockObject_MockObject */
    protected $client;

    public function setUp()
    {
        $this->client = XMock::of(Client::class);
    }

    public function tearDown()
    {
        unset($this->client);
    }

    /**
     * @param AbstractUrlBuilder|String $url
     * @param mixed                     $return
     */
    protected function setupClientMockGet($url, $return)
    {
        $url = (is_object($url) ? $url->toString() : $url);

        $this->mockMethod('get', $this->once(), $return, $url);
    }

    /**
     * Mock a method of specified mock object
     *
     * @param string                                          $method
     * @param \PHPUnit_Framework_MockObject_Matcher_Invocation $invocation
     * @param mixed|\PHPUnit_Framework_MockObject_Stub         $returnValue
     * @param array[]                                         $withParams (PHPUnit_Framework_Constraint or value)
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockMethod(
        $method,
        $invocation = null,
        $returnValue = null,
        $withParams = null
    ) {
        $method = $this->client
            ->expects($invocation ? $invocation : $this->any())
            ->method($method);

        if (is_array($withParams) && !empty($withParams)) {
            $method->withConsecutive($withParams);
        } elseif (!empty($withParams)) {
            $method->with($this->equalTo($withParams));
        }

        if ($returnValue !== null) {
            if ($returnValue instanceof \PHPUnit_Framework_MockObject_Stub) {
                $method->will($returnValue);
            } elseif ($returnValue instanceof \Exception) {
                $method->willThrowException($returnValue);
            } else {
                $method->willReturn($returnValue);
            }
        }
    }
}
