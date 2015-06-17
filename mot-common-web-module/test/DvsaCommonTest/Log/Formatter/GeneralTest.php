<?php

namespace DvsaCommonTest\Log\Formatter;

use DvsaCommon\Log\Formatter\General;

class GeneralTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var General
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new General();
    }

    /**
     * Extra data should be encoded to a json string
     */
    public function testExtraGetsEncodedToJson()
    {
        $extraData = [
            'foo' => 'bar',
            'encode' => true,
            '__dvsa_metadata__' => [
                'openAmSessionToken' => 'bar',
                'openAmUuid' => 12345,
            ]
        ];

        $expectedString = json_encode(['foo' => 'bar', 'encode' => true]);
        $output = $this->formatter->format(['extra' => $extraData]);

        $this->assertStringEndsWith($expectedString, $output);
    }

    /**
     * This will test the log message in its full format. See
     * https://wiki.i-env.net/display/EA/Logging+Formats for requirements
     */
    public function testExpectedLogMessageInFullFormat()
    {
        $expectedPriority = 7;
        $expectedPriorityName = 'DEBUG';
        $expectedLogEntryType = 'Foo';
        $expectedOpenAmUuid = '1234';
        $expectedOpenSessionToken = 'openam-token';
        $expectedClass = __CLASS__;
        $expectedMessage = 'This is a test message';
        $expectedCorrelationId = 'class-1234';
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
                    'class' => $expectedClass,
                ]
            ]
        ];

        // omit timestamp as we can't be sure of microsecond value
        $expectedString = vsprintf(
            '%s||%s||%s||%s||%s||%s||%s',
            [
                $expectedLogEntryType,
                $expectedOpenAmUuid,
                $expectedOpenSessionToken,
                $expectedCorrelationId,
                $expectedClass,
                $expectedMessage,
                json_encode($expectedExtra),
            ]
        );

        $this->assertStringEndsWith($expectedString, $this->formatter->format($event));
    }
}
