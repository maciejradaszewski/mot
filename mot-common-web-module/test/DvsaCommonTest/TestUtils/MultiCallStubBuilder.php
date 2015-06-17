<?php

namespace DvsaCommonTest\TestUtils;

/**
 * A testing helper to enable mocks that return different values dependently on the
 * method parameters passed in to mocked method. Only the first matched predicate
 * is always return, even if there is more.
 *
 * Class MultiCallStub
 *
 * @package DvsaCommonTest\TestUtils
 */
class MultiCallStubBuilder
{

    private $matchingPredicateList = [];

    public static function of()
    {
        return new static();
    }

    private function __construct()
    {
    }

    /**
     * @param object|array $argsMatchers
     *      A single element or an array consisting of primitive elements or ab object of a class extending
     *      PHPUnit_Framework_Constraint. The number of elements should match the number of arguments of the call
     *      to be matched.
     *      Examples:
     *      1
     *      5, 'String'
     *      IsNull, IsArray
     *
     * @param $result
     *      response value of a stub
     *
     * @return $this
     */
    public function add($argsMatchers, $result)
    {
        $this->matchingPredicateList[] = [$argsMatchers, $result];
        return $this;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_Stub_ReturnCallback
     */
    public function build()
    {
        $testCase = BacktraceTestCaseFinder::find();
        return $testCase->returnCallback(
            function () {
                $callArgs = func_get_args();
                foreach ($this->matchingPredicateList as $matchingTuple) {
                    $argsMatchers = $matchingTuple[0];
                    $result = $matchingTuple[1];
                    // unify format if there is only one element in the tuple
                    if (!is_array($argsMatchers)) {
                        $argsMatchers = [$argsMatchers];
                    }
                    $argsMatched = 0;
                    $callArgsCount = count($callArgs);
                    for (; $argsMatched < $callArgsCount; ++$argsMatched) {
                        $argMatcher = current($argsMatchers);
                        $argMatcher = $this->wrapMatcherIfNecessary($argMatcher);
                        if (!$argMatcher->evaluate($callArgs[$argsMatched], '', true)) {
                            break;
                        }
                        next($argsMatchers);
                    }
                    if ($callArgsCount === $argsMatched) {
                        if ($result instanceOf \PHPUnit_Framework_MockObject_Stub_Exception) {
                            $result->invoke(new \PHPUnit_Framework_MockObject_Invocation_Static("", "", []));
                        }
                        return $result;
                    }
                }
                throw new \PHPUnit_Framework_ExpectationFailedException("Could not find proper stub response");
            }
        );
    }

    /**
     * @param $argMatcher
     *
     * @return \PHPUnit_Framework_Constraint
     */
    private function wrapMatcherIfNecessary($argMatcher)
    {
        if (!($argMatcher instanceof \PHPUnit_Framework_Constraint)) {
            $argMatcher = new \PHPUnit_Framework_Constraint_IsEqual($argMatcher);
        }
        return $argMatcher;
    }
}
