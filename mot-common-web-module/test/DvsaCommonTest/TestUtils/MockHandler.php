<?php
namespace DvsaCommonTest\TestUtils;

use PHPUnit_Framework_TestCase;

/**
 * Class MockHandler
 *
 * @package DvsaCommonApiTest\Utils
 */
class MockHandler
{
    protected $mock;
    protected $at;
    protected $phpUnitTestCase;

    const DEFAULT_START_FROM_ZERO = 0;

    public static function of($mock)
    {
        return new static($mock, BacktraceTestCaseFinder::find());
    }

    public function __construct(
        $mock,
        \PHPUnit_Framework_TestCase $phpUnitTestCase,
        $sequence = self::DEFAULT_START_FROM_ZERO
    ) {
        $this->mock = $mock;
        $this->at = $sequence;
        $this->phpUnitTestCase = $phpUnitTestCase;
    }

    public function removeNext($entity)
    {
        return $this->methodNext('remove', $entity);
    }

    public function persistNext($entity)
    {
        return $this->methodNext('persist', $entity);
    }

    public function find()
    {
        return $this->next('find');
    }

    public function getRepository($entityClass)
    {
        return $this->next('getRepository')->with($entityClass);
    }

    /**
     * @param string $method
     *
     * @return \PHPUnit_Framework_MockObject_Builder_InvocationMocker
     */
    public function next($method)
    {
        $result = $this->mock->expects($this->phpUnitTestCase->at($this->at))->method($method);
        $this->at += 1;

        return $result;
    }

    public function callNext($method)
    {
        $this->next($method);

        return $this;
    }

    protected function methodNext($method, $entityClass)
    {
        $this->next($method)->with($this->phpUnitTestCase->isInstanceOf($entityClass));

        return $this;
    }
}