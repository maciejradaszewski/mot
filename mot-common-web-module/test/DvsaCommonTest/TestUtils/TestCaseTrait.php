<?php

namespace DvsaCommonTest\TestUtils;

/**
 * Common test functionality
 */
trait TestCaseTrait
{
    /**
     * Mock a method of specified mock object
     *
     * @param \PHPUnit_Framework_MockObject_MockObject         $mock
     * @param string                                           $method
     * @param \PHPUnit_Framework_MockObject_Matcher_Invocation $invocation
     * @param mixed|\PHPUnit_Framework_MockObject_Stub         $returnValue
     * @param array[]                                          $withParams (PHPUnit_Framework_Constraint or value)
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function mockMethod(
        \PHPUnit_Framework_MockObject_MockObject $mock,
        $method,
        $invocation = null,
        $returnValue = null,
        $withParams = null
    ) {
        $method = $mock
            ->expects($invocation ? $invocation : $this->any())
            ->method($method);

        if (is_array($withParams) && !empty($withParams)) {
            $method->withConsecutive($withParams);
        } elseif (!empty($withParams)) {
            $method->with($this->equalTo($withParams));
        } else {
            $method->withAnyParameters();
        }

        if ($returnValue !== null) {
            if ($returnValue instanceof \PHPUnit_Framework_MockObject_Stub) {
                $method->will($returnValue);
            } elseif ($returnValue instanceof \Exception) {
                $method->willThrowException($returnValue);
            } elseif ($returnValue instanceof \Closure) {
                $method->willReturnCallback($returnValue);
            } else {
                $method->willReturn($returnValue);
            }
        }

        return $mock;
    }
}
