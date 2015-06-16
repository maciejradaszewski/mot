<?php

namespace DvsaCommon\Messages;

/**
 * Errors for date and date range
 */
class DateErrors
{
    const ERR_DATE_RANGE = 'A date range of more than 31 days has been entered. Please reduce the range';
    const ERR_DATE_MISSING = 'Enter a date in the format dd mm yyyy';
    const ERR_DATE_INVALID = 'Given date does not exist';
    const ERR_DATE_AFTER = 'Date From can\'t be after Date To';
}
