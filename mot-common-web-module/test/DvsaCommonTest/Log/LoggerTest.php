<?php


namespace DvsaCommonTest\Log;


use DvsaCommon\Log\Logger;
use Zend\Log\Writer\Mock;


class LoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Mock
     */
    protected $writer;

    public function setUp()
    {
        $this->writer = new Mock();
        $this->logger = new Logger();
        $this->logger->addWriter($this->writer);
    }

    public function testLoggingExceptionsCapturesExceptionProperties()
    {
        $exception = new \Exception('test exception', 1203);
        $this->logger->setException($exception);

        $this->logger->err('oops');

        $metadata = $this->getMetadata();

        $this->assertEquals($exception->getTraceAsString(), $metadata['stacktrace']);
        $this->assertEquals($exception->getCode(), $metadata['errorCode']);
        $this->assertEquals(get_class($exception), $metadata['exceptionType']);
    }

    public function testOpenAmUuidIsPassedToWriter()
    {
        $expectedUuid = 'open-uuid';

        $this->logger->setOpenAmUuid($expectedUuid);
        $this->logger->debug('test');

        $metadata = $this->getMetadata();
        $this->assertEquals($expectedUuid, $metadata['openAmUuid']);
    }

    public function testOpenAmSessionTokenIsPassedToWriter()
    {
        $expectedSessionToken = 'openam-session-token';

        $this->logger->setOpenAmSessionToken($expectedSessionToken);
        $this->logger->debug('test');

        $metadata = $this->getMetadata();
        $this->assertEquals($expectedSessionToken, $metadata['openAmSessionToken']);
    }

    public function testCorrelationIdIsPassedToWriter()
    {
        $expectedCorrelationId = 'correlation-id';

        $this->logger->setCorrelationId($expectedCorrelationId);
        $this->logger->debug('test');

        $metadata = $this->getMetadata();
        $this->assertEquals($expectedCorrelationId, $metadata['correlationId']);
    }

    public function testLogEntryTypeIsPassedToWriter()
    {
        $expectedLogEntryType = 'general';

        $this->logger->setLogEntryType($expectedLogEntryType);
        $this->logger->debug('test');

        $metadata = $this->getMetadata();
        $this->assertEquals($expectedLogEntryType, $metadata['logEntryType']);
    }

    public function testDefaultLogTypeIsGeneral()
    {
        $expectedLogType = 'General';

        $this->logger->debug('test');
        $this->assertEquals($expectedLogType, $this->getMetadata()['logEntryType']);
    }

    public function testLogEntryTypeIsExceptionWhenExceptionIsSet()
    {
        $expectedLogType = 'Exception';

        $this->logger->setException(new \Exception());
        $this->logger->err('oops');

        $this->assertEquals($expectedLogType, $this->getMetadata()['logEntryType']);
    }

    /**
     * @return array
     */
    protected function getMetadata()
    {
        return $this->writer->events[0]['extra']['__dvsa_metadata__'];
    }
}
