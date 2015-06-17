<?php

namespace DvsaCommon\Messages;

/**
 * Errors for date and date range
 */
class DateErrors
{
    const DATE_INVALID = "'%s' Date is invalid.";
    const DATE_TOO_OLD = "'%s' Date is too old. Please select a date after the year 1900.";
    const DATE_FUTURE = "'%s' Date cannot be in the future.";

    const INCORRECT_INTERVAL = "’%s’ Date is older than ’%s’ date.";
}
