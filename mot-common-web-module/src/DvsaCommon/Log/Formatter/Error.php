<?php


namespace DvsaCommon\Log\Formatter;

/**
 * This class will format the log message for any exception being thrown in
 * the application.
 *
 * @package DvsaCommon\Log\Formatter
 */
class Error extends General
{
    /**
     * Exception Formatter
     */
    public function __construct()
    {
        parent::__construct();

        $output = '%logEntryType%||%openAmUuid%||%openAmSessionToken%||'
            . '%correlationId%||%exceptionType%||%errorCode%||%message%||%extra%'
            . '||%stacktrace%';

        $this->output = $output;
    }
}
