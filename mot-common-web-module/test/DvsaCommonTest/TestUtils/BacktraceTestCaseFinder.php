<?php


namespace DvsaCommonTest\TestUtils;

/**
 * Helper class to locate PHPUnit test class in the invocation stack.
 * As long as PHPUnit_Framework_TestCase class is used as a ultimate base class
 * it is always possible to do so.
 * Very useful in decoupling various test helpers from phpunit base class.
 *
 * Class BacktraceTestCaseFinder
 *
 * @package DvsaCommonTest\TestUtils
 */
class BacktraceTestCaseFinder
{
    /* This is to reduce the requested number of frames to avoid slowing down the test
       execution too much.
    */
    const FRAMES_LIMIT = 5;

    /**
     * @return null|\PHPUnit_Framework_TestCase
     * @throws \Exception
     */
    public static function find()
    {
        $testCase = null;
        $stacktrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, self::FRAMES_LIMIT);
        $stacktraceLength = count($stacktrace);
        for ($i = 1; $i < $stacktraceLength; ++$i) {
            $frame = $stacktrace[$i];
            $frameObject = isset($frame['object']) ? $frame['object'] : null;
            if ($frameObject === null) {
                continue;
            }
            if ($frameObject instanceof \PHPUnit_Framework_TestCase) {
                $testCase = $frameObject;
                break;
            }
        }
        if ($testCase === null) {
            throw new \Exception("Could not find a test case. Increase the maximum frames limit if necessary");
        }
        return $testCase;
    }
}
