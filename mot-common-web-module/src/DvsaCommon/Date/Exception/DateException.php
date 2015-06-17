<?php

namespace DvsaCommon\Date\Exception;

/**
 * Generic exception for DateUtils class (handles using \DateTime class which is very weird in PHP)
 */
class DateException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
