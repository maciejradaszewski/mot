<?php
namespace DvsaCommon\Date\Exception;

/**
 * Exception thrown by DateUtils class when datetime does not exist
 */
class NonexistentDateTimeException extends DateException
{


    const MESSAGE = 'Given datetime does not exist (%s)';

    public function __construct($date, $message = self::MESSAGE)
    {
        parent::__construct(sprintf($message, $date));
    }
}
