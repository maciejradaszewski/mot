<?php

namespace DvsaCommon\Messages;

/**
 * Errors for date and date range
 */
class DateErrors
{
    const RANGE_31D = 'A date range of more than 31 days has been entered. Please reduce the range';
    const INVALID_FORMAT = 'Enter a date in the format dd mm yyyy';
    const NOT_EXIST = 'Given date does not exist';
    const AFTER_TO = 'Date From can\'t be after Date To';
    const IN_FUTURE = "Date cannot be in the future.";
}
