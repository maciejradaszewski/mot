<?php


namespace DvsaCommonTest\TestUtils;


/**
 * A helper for testing to be able to change expectations multiple times across multiple tests,
 * which is impossible for standard phpunit mocks
 *
 * Class OverridableMockBuilder
 *
 * @package DvsaCommonApiTest\Utils
 * @see OverridableExpectationBuilder
 */
class OverridableMockBuilder
{

    private $expectactations = [];
    /**
     * @var \PHPUnit_Framework_TestCase $testCase
     */
    private $testCase;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $baseMock
     */
    private $baseMock;

    /**
     * Accepts either a string that is a class name of the class that should be mocked
     * or a base mock that will be used to set up new expectations
     *
     * @param string|\PHPUnit_Framework_MockObject_MockObject $arg
     *
     * @return OverridableMockBuilder
     */
    public static function of($arg)
    {
        return new static($arg);
    }


    /**
     * @param $arg
     */
    private function __construct($arg)
    {
        $baseMock = null;
        if ($arg instanceof \PHPUnit_Framework_MockObject_MockObject) {
            $baseMock = $arg;
        } elseif (is_string($arg)) {
            $class = $arg;
            $baseMock = XMock::of($class);
        }

        $this->testCase = BacktraceTestCaseFinder::find();
        $this->baseMock = $baseMock;
    }

    /**
     * @param OverridableExpectationBuilder $builder
     */
    public function setExpectation(OverridableExpectationBuilder $builder)
    {
        $this->expectactations[$builder->getMethod()] = $builder;
    }

    /**
     * @return mixed
     */
    public function build()
    {
        $mock = $this->baseMock;
        /** @var OverridableExpectationBuilder $expectationBuilder */
        foreach ($this->expectactations as $method => $expectationBuilder) {
            $chain = $mock->expects($this->testCase->any());
            $chain = $chain->method($method);
            if ($expectationBuilder->getWith()) {
                $chain = $chain->with($expectationBuilder->getWith());
            }
            if ($expectationBuilder->getWillStub()) {
                $chain->will($expectationBuilder->getWillStub());
            }
        }

        return $mock;
    }
}
