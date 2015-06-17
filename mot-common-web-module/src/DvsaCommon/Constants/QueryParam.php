<?php

namespace DvsaCommon\Constants;

/**
 * Holds query parameters for common sorting functions
 */
class QueryParam
{
    const DATE_TO_DAY = 'dateToDay';
    const DATE_TO_MONTH = 'dateToMonth';
    const DATE_TO_YEAR = 'dateToYear';
    const DATE_FROM_DAY = 'dateFromDay';
    const DATE_FROM_MONTH = 'dateFromMonth';
    const DATE_FROM_YEAR = 'dateFromYear';

    const DATE_RANGE = 'dateRange';
    const DATE_RANGE_TODAY = 'today';
    const DATE_RANGE_7_DAY = '7days';
    const DATE_RANGE_30_DAY = '30days';
    const DATE_RANGE_1_YEAR = '1year';
    const DATE_RANGE_CUSTOM = 'custom';

    const DATE_FROM = 'dateFrom';
    const DATE_TO = 'dateTo';

    const SEARCH_TEXT = 'searchText';

    /**
     * Search params relate to \DvsaCommonApi\Model\SearchParam
     * Changing these will affect the API side of things
     */
    const SORT_DIRECTION_ASC    = 'asc';
    const SORT_DIRECTION_DESC   = 'desc';
    const SORT_COLUMN_ID        = 'sortColumnId';
    const SORT_DIRECTION        = 'sortDirection';
    const ROW_COUNT             = 'rowCount';
    const START                 = 'start';

    public static function getFormDateParams()
    {
        return [
            self::DATE_TO_DAY,
            self::DATE_TO_MONTH,
            self::DATE_TO_YEAR,
            self::DATE_FROM_DAY,
            self::DATE_FROM_MONTH,
            self::DATE_FROM_YEAR,
        ];
    }

    public static function getDateRange()
    {
        return [
            self::DATE_RANGE_TODAY => 'Today',
            self::DATE_RANGE_7_DAY => 'Last 7 days',
            self::DATE_RANGE_30_DAY => 'Last 30 days',
            self::DATE_RANGE_1_YEAR => 'Last year',
            self::DATE_RANGE_CUSTOM => 'Custom'
        ];
    }
}
