<?php

namespace DvsaCommon\Constants;

/**
 * Types of odometer readings. There is no database lookup for it.
 */
class OdometerReadingResultType extends BaseEnumeration
{
    const OK = 'OK';
    const NO_ODOMETER = 'NO_METER';
    // not readable
    const NOT_READABLE = 'NOT_READ';
}
