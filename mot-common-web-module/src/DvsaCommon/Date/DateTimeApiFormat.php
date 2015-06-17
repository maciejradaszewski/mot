<?php

namespace DvsaCommon\Date;

/**
 * Class responsible for formatting datetime that is output by API
 *
 * There can be two formats the date can be output:
 * + date only - when time does NOT matter
 * + date and time in UTC
 */
class DateTimeApiFormat
{
    const FORMAT_ISO_8601_UTC_TZ = 'Y-m-d\TH:i:s\Z';
    const FORMAT_ISO_8601_DATE_ONLY = 'Y-m-d';

    /**
     * Formats DateTime to iso8601 UTC format -> see FORMAT_ISO_8601_UTC_TZ
     * Use when time part matters
     *
     * @param \DateTime $dateTime
     *
     * @return string
     */
    public static function dateTime($dateTime)
    {
        return is_null($dateTime) ? null : DateUtils::toUtc($dateTime)->format(self::FORMAT_ISO_8601_UTC_TZ);
    }

    /**
     * Formats DateTime to iso8601 date only. Use when time part is not applicable
     *
     * @param \DateTime $date
     *
     * @return string
     */
    public static function date($date)
    {
        return is_null($date) ? null : $date->format(self::FORMAT_ISO_8601_DATE_ONLY);
    }
}
