<?php

namespace DvsaCommon\Constants;

/**
 * Contains query parameters for mot test search
 */
class SearchParamConst
{
    /**
     * @deprecated use SORT_BY
     */
    const SORT_COLUMN_ID = 'sortColumnId';
    const SORT_BY = 'sortBy';
    const SORT_DIRECTION = 'sortDirection';

    const ROW_COUNT = 'rowCount';
    const PAGE_NR = 'pageNumber';
    const START = 'start';

    const FORMAT = 'format';

    // @TODO rename constants - remove SEARCH_;
    const SEARCH_SEARCH_FILTER                  = 'searchFilter';
    const SEARCH_SEARCH_RECENT_QUERY_PARAM      = 'searchRecent';

    const SEARCH_TESTER_ID_QUERY_PARAM          = 'tester';
    const SEARCH_SITE_NUMBER_QUERY_PARAM        = 'siteNumber';
    const SEARCH_VRM_QUERY_PARAM                = 'vrm';
    const SEARCH_VIN_QUERY_PARAM                = 'vin';
    const SEARCH_VEHICLE_ID_QUERY_PARAM         = 'vehicleId';
    const SEARCH_DATE_FROM_QUERY_PARAM          = 'dateFrom';
    const SEARCH_DATE_TO_QUERY_PARAM            = 'dateTo';
    const SEARCH_TESTER_USERNAME_QUERY_PARAM    = 'testerUserName';
    const ORGANISATION_ID                       = 'organisationId';

    const FORMAT_DATA_CSV = 'DATA_CSV';
    const FORMAT_DATA_TABLES = 'DATA_TABLES';
    const FORMAT_DATA_OBJECT = 'DATA_OBJECT';
    const FORMAT_TYPE_AHEAD = 'TYPE_AHEAD';

    const SORT_DIRECTION_ASC = 'ASC';
    const SORT_DIRECTION_DESC = 'DESC';

    const DEF_ROWS_COUNT = 10;
    const DEF_PAGE_NR = 1;
}
