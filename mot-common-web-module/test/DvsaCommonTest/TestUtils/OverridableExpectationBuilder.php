<?php


namespace DvsaCommonTest\TestUtils;

/**
 * Class OverridableExpectationBuilder
 *
 * @package DvsaCommonApiTest\Utils
 */
class OverridableExpectationBuilder
{

    private $method;
    private $willStub;
    private $with;

    /**
     *
     */
    private function __construct()
    {

    }

    /**
     * @return OverridableExpectationBuilder
     */
    public static function create()
    {
        return new OverridableExpectationBuilder();
    }

    /**
     * @param $method
     * @param $willStub
     *
     * @return $this
     */
    public static function withMethodResult($method, $willStub)
    {
        return (new OverridableExpectationBuilder())->method($method)->willStub($willStub);
    }

    /**
     * @return mixed
     */
    public function getWillStub()
    {
        return $this->willStub;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getWith()
    {
        return $this->with;
    }

    /**
     * @param $willStub
     *
     * @return $this
     */
    public function willStub($willStub)
    {
        $this->willStub = $willStub;
        return $this;
    }

    /**
     * @param $method
     *
     * @return $this
     */
    public function method($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param $arg
     *
     * @return $this
     */
    public function with($arg)
    {
        $this->with = $arg;
        return $this;
    }
}
