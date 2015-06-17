<?php

namespace DvsaCommon\Date\Exception;

/**
 * Exception throw by DateUtils class when incorrect format given
 *
 * See \DvsaCommon\Date\DateUtils class to find out valid formats
 */
class IncorrectDateFormatException extends DateException
{


    const MESSAGE = 'Incorrect date format. Expected: %s, given: %s';

    public function __construct($expectedFormat, $dateProvided, $message = self::MESSAGE)
    {
        parent::__construct(sprintf($message, $expectedFormat, $dateProvided));
    }
}
