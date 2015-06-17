<?php
namespace DvsaCommon\Date\Exception;

/**
 * Exception thrown by DateUtils class when date does not exist, e.g. 2010-02-31
 */
class NonexistentDateException extends DateException
{


    const MESSAGE = 'Given date does not exist (%s)';

    public function __construct($date, $message = self::MESSAGE)
    {
        parent::__construct(sprintf($message, $date));
    }
}
