<?php


namespace DvsaCommonTest\Log\Formatter;


use DvsaCommon\Log\Formatter\Error;


class ErrorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the output format matches the requirements on
     * https://wiki.i-env.net/display/EA/Logging+Formats
     */
    public function testOutputFormatContainsRelevantProperties()
    {
        $formatter = new Error();

        $expectedPriority = 7;
        $expectedPriorityName = 'DEBUG';
        $expectedLogEntryType = 'Foo';
        $expectedOpenAmUuid = '1234';
        $expectedOpenSessionToken = 'openam-token';
        $expectedClass = __CLASS__;
        $expectedMessage = 'This is a test message';
        $expectedCorrelationId = 'class-1234';
        $expectedExceptionType = 'Exception';
        $expectedExceptionCode = 123;
        $expectedStackTrace = 'trace';
        $expectedExtra = ['foo' => 'bar'];

        $event = [
            'priority' => $expectedPriority,
            'priorityName' => $expectedPriorityName,
            'message' => $expectedMessage,
            'extra' => [
                'foo' => 'bar',
                '__dvsa_metadata__' => [
                    'correlationId' => $expectedCorrelationId,
                    'logEntryType' => $expectedLogEntryType,
                    'openAmUuid' => $expectedOpenAmUuid,
                    'openAmSessionToken' => $expectedOpenSessionToken,
                    'errorCode' => $expectedExceptionCode,
                    'exceptionType' => $expectedExceptionType,
                    'stacktrace' => $expectedStackTrace,
                    'class' => $expectedClass,
                ]
            ]
        ];

        $expectedString = vsprintf(
            '%s||%s||%s||%s||%s||%s||%s||%s||%s',
            [
                $expectedLogEntryType,
                $expectedOpenAmUuid,
                $expectedOpenSessionToken,
                $expectedCorrelationId,
                $expectedExceptionType,
                $expectedExceptionCode,
                $expectedMessage,
                json_encode($expectedExtra),
                $expectedStackTrace
            ]
        );

        $this->assertStringEndsWith($expectedString, $formatter->format($event));
    }
}
