<?php


namespace DvsaCommon\Log;

use Zend\Debug\Debug;
use Zend\Log\Filter\Priority;
use Zend\Log\Logger as ZendLogger;
use Zend\Log\Processor\Backtrace;
use Zend\Log\Writer\Syslog;


/**
 * This is a bespoke logger class for the DVSA MOT project. It tracks
 * additional properties in relation to the request. It also tracks extra info
 * when an exception is thrown.
 *
 * @package DvsaCommon\Log
 */
class Logger extends ZendLogger
{
    /**
     * @var string
     */
    private $openAmUuid = '';

    /**
     * @var string
     */
    private $openAmSessionToken = '';

    /**
     * @var string
     */
    private $correlationId = '';

    /**
     * @var string
     */
    private $logEntryType = 'General';

    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @param \Exception $exception
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @param string $correlationId
     */
    public function setCorrelationId($correlationId)
    {
        $this->correlationId = $correlationId;
    }

    /**
     * @param string $openAmSessionToken
     */
    public function setOpenAmSessionToken($openAmSessionToken)
    {
        $this->openAmSessionToken = $openAmSessionToken;
    }

    /**
     * @param string $openAmUuid
     */
    public function setOpenAmUuid($openAmUuid)
    {
        $this->openAmUuid = $openAmUuid;
    }

    /**
     * @param string $logEntryType
     */
    public function setLogEntryType($logEntryType)
    {
        $this->logEntryType = $logEntryType;
    }

    /**
     * Log a message.
     *
     * {@inheritDoc}
     *
     * @param int $priority
     * @param mixed $message
     * @param array $extra
     * @return void|ZendLogger
     */
    public function log($priority, $message, $extra = [])
    {
        // add extra dvsa specific metadata parameters to the extra array
        $metadata = [
            'openAmUuid' => $this->openAmUuid,
            'openAmSessionToken' => $this->openAmSessionToken,
            'correlationId' => $this->correlationId,
            'logEntryType' => $this->logEntryType,
        ];

        if ($this->exception) {
            $metadata['logEntryType'] = 'Exception';
            $metadata['stacktrace'] = $this->exception->getTraceAsString();
            $metadata['errorCode'] = $this->exception->getCode();
            $metadata['exceptionType'] = get_class($this->exception);
        }

        $extra['__dvsa_metadata__'] = $metadata;

        return parent::log($priority, $message, $extra);
    }
}
