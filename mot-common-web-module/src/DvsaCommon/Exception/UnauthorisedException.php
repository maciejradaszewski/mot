<?php

namespace DvsaCommon\Exception;

use DvsaCommon\Utility\ArrayUtils;

/**
 * Exception thrown when required permission is not granted
 */
class UnauthorisedException extends \Exception
{
    private $debugInfo = [];

    public function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * Used to set debug information (structure @see getDebugInfo).
     * Main use is to restore information from API layer.
     *
     * @param array $debugInfo
     *
     * @return $this
     */
    public function setDebugInfo(array $debugInfo)
    {
        $this->debugInfo = $debugInfo;

        return $this;
    }

    /**
     * Returns an array of debug information consisting of:
     *  file - the file where exception originally occured
     *  line - the line in the file where exception originally occured
     *  trace - the original trace of the exception
     *
     * @return array
     */
    public function getDebugInfo()
    {
        return [
            'file'  => ArrayUtils::tryGet($this->debugInfo, 'file', $this->file),
            'line'  => ArrayUtils::tryGet($this->debugInfo, 'line', $this->line),
            'trace' => ArrayUtils::tryGet($this->debugInfo, 'trace', $this->getTraceAsString())
        ];
    }
}
