<?php
namespace DvsaCommon\Date\Exception;

/**
 * Exception thrown by DateUtils class
 * when \DateTime::getLastErrors() returns array with errors after creating the object
 */
class DateTimeErrorException extends DateException
{


    public function __construct($errors)
    {
        parent::__construct(is_array($errors) ? join(". \n", $errors) : strval($errors));
    }
}
