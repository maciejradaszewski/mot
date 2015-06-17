<?php


namespace DvsaCommonTest\TestUtils;


/**
 * PHPUnit constraint used to capture arguments in with() clause
 * of expectation.
 * Example usage
 *
 * $capturer = new ArgCapture($this)
 * expects(...)->method(...)->with($capturer())->...
 * ...
 *
 * $capturedArg = $capturer->get();
 * assertThat($capturedArg, ...)
 *
 * Class ArgCapture
 *
 * @package DvsaCommonApiTest\Utils
 */
class ArgCapture
{

    private $capturedArg;

    private $testInstance;

    /**
     *
     */
    private function __construct()
    {
        $this->testInstance = BacktraceTestCaseFinder::find();
    }

    /**
     * @return ArgCapture
     */
    public static function create()
    {
        return new ArgCapture();
    }

    /**
     * When called release a capture function
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this->testInstance->callback(
            function ($arg) {
                $this->capturedArg = $arg;
                return true;
            }
        );
    }

    public function get()
    {
        return $this->capturedArg;
    }
}
