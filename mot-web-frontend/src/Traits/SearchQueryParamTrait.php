<?php
namespace Dvsa\Mot\Frontend\Traits;

use Application\Service\ReportBuilder\Service as ReportBuilderService;
use DvsaCommon\Constants\QueryParam;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Date\Exception\DateException;
use DvsaCommon\Date\Exception\IncorrectDateFormatException;
use Zend\View\ViewEvent;

trait SearchQueryParamTrait
{

    private $dateFormFields;

    private $apiData;

    private $tableName;

    private $page;

    public function __construct()
    {
        $this->dateFormFields = $this->getDateFormFields();
    }

    /**
     * Builds the report variables for use in the view
     *
     * @param \Zend\Form\Form $form
     * @param \Zend\Http\Request $request
     * @param int $page
     * @param string $tableName
     * @param string $apiPath
     * @param array $fixedParams
     * @param array $additionalParams
     * @return array
     */
    private function buildReport(
        \Zend\Form\Form $form,
        \Zend\Http\Request $request,
        $page,
        $tableName,
        $apiPath,
        $fixedParams = [],
        $additionalParams = []
    ) {
        $this->tableName = $tableName;
        $this->form = $form;
        $this->page = $page;

        $variables = [];

        $searchParams = $this->buildSearchParams($request, $page, $fixedParams, $additionalParams);
        $this->apiData = $this->getWithParams($apiPath, $searchParams);

        $variables['form'] = $form;
        $variables['params'] = $request->getQuery()->toArray();
        $variables['paramsNoDates'] = $this->removeUnusedParams($variables['params']);
        $variables['dateRanges'] = QueryParam::getDateRange();

        //if the form has errors, don't go any further
        if (!$this->form->isValid()) {
            $messages = $this->form->getMessages();
            foreach ($messages as $message) {
                $this->addErrorMessages($message);
            }

            $variables = $this->addSearchErrorViewVars($variables);

            return $variables;
        }

        //check whether we were able to build search params
        if (!$searchParams) {
            $variables = $this->addSearchErrorViewVars($variables);

            return $variables;
        }

        $variables['currentDateRange'] = (isset($searchParams['dateRange']) ? $searchParams['dateRange'] : '');
        $variables['totalResultCount'] = $this->apiData['totalResultCount'];
        $variables['table'] = $this->buildTable($tableName, $this->apiData, $page);
        $variables['dateRangeLabel'] = $this->getCurrentDateRangeLabel();
        $variables['route'] = $request->getUri();

        return $variables;
    }

    private function addSearchErrorViewVars($variables = [])
    {
        $variables['form'] = $this->form;
        $variables['totalResultCount'] = 0;
        $variables['table'] = $this->buildTable(
            $this->tableName,
            [
                'data' => [],
                'totalResultCount' => 0
            ],
            $this->page
        );
        $variables['dateRangeLabel'] = '';
        $variables['currentDateRange'] = 'custom';

        return $variables;
    }

    private function getCurrentDateRangeLabel()
    {
        $dateRangeParam = $this->params()->fromQuery(QueryParam::DATE_RANGE);

        if (isset(QueryParam::getDateRange()[$dateRangeParam]) && $dateRangeParam != QueryParam::DATE_RANGE_CUSTOM) {
            return mb_strtolower(QueryParam::getDateRange()[$dateRangeParam], 'UTF-8');
        }

        if ((strlen($this->params()->fromQuery(QueryParam::DATE_FROM_YEAR)) > 0
            && strlen($this->params()->fromQuery(QueryParam::DATE_TO_YEAR)) > 0)
        ) {
            $dates = $this->getDatesFromRequest($this->getRequest());

            return 'between ' . date('d/m/Y', strtotime($dates['dateFrom'])) .
            ' and ' . date('d/m/Y', strtotime($dates['dateTo']));
        }

        return '';
    }

    /**
     * Builds search params based on the request
     *
     * Fixed params are params which have a fixed value on each search
     *
     * Additional params are params which are non-generic and specific to a
     * report, expects array(paramName => defaultValue)
     *
     * @param \Zend\Http\Request $request
     * @param int $page
     * @param array $fixedParams
     * @param array $additionalParams
     * @return array
     */
    private function buildSearchParams(
        \Zend\Http\Request $request,
        $page = 1,
        $fixedParams = [],
        $additionalParams = []
    ) {
        $dateSearchParams = $this->buildDateSearchParams($request, new \DateTime());

        //error in posted dates
        if (!$dateSearchParams) {
            return false;
        }

        $searchParams = array_merge(
            $this->getQueryParams($request, $additionalParams),
            $this->buildDateSearchParams($request, new \DateTime()),
            $fixedParams
        );

        //calculate offset
        $searchParams[QueryParam::START] = $this->calculateOffset($page, $searchParams[QueryParam::ROW_COUNT]);

        return $searchParams;
    }

    /**
     *
     * Gets the params from a query string, otherwise uses defaults
     * Will default to sorting descending by column zero, on the assumption
     * column zero is likely to be a date or ID column. This will provide the
     * most recent records first.
     *
     * @param \Zend\Http\Request $request
     * @param $additionalParams
     * @return array
     */
    private function getQueryParams(\Zend\Http\Request $request, $additionalParams = [])
    {
        $searchData = [];

        $searchData[QueryParam::SORT_COLUMN_ID] = $request->getQuery(QueryParam::SORT_COLUMN_ID, 0);
        $searchData[QueryParam::SORT_DIRECTION] = strtoupper(
            $request->getQuery(
                QueryParam::SORT_DIRECTION,
                QueryParam::SORT_DIRECTION_DESC
            )
        );
        $searchData[QueryParam::ROW_COUNT] = (int)$request->getQuery(QueryParam::ROW_COUNT, 10);
        $searchData[QueryParam::DATE_RANGE] = $request->getQuery(QueryParam::DATE_RANGE, null);

        foreach ($additionalParams as $param => $default) {
            $searchData[$param] = $request->getQuery($param, $default);
        }

        return $searchData;
    }

    /**
     * Goes through the date from/to fields of a posted form,
     * creates dates in a format suitable for searching
     *
     * Also validates the individual date values, will move this into the form
     * annotation/input filter eventually
     *
     * @param \Zend\Http\Request $request
     * @return array
     */
    private function getDatesFromRequest(\Zend\Http\Request $request)
    {
        $dates = [];
        $allErrors = [];

        $dateFromDay = $request->getQuery(QueryParam::DATE_FROM_DAY);
        $dateFromMonth = $request->getQuery(QueryParam::DATE_FROM_MONTH);
        $dateFromYear = $request->getQuery(QueryParam::DATE_FROM_YEAR);
        $dateToDay = $request->getQuery(QueryParam::DATE_TO_DAY);
        $dateToMonth = $request->getQuery(QueryParam::DATE_TO_MONTH);
        $dateToYear = $request->getQuery(QueryParam::DATE_TO_YEAR);

        //defaults which can be used as a last resort
        $earliestDateString = '2000-01-01';
        $currentDateString = date('Y-m-d');

        //only if all three fields of a date are empty, we use the default
        if ($dateFromDay === null && $dateFromMonth === null && $dateFromYear === null) {
            $dates['dateFrom'] = $earliestDateString;
        } else {
            $dateFromErrors = $this->processDateParts('Date From', $dateFromDay, $dateFromMonth, $dateFromYear);

            if (!empty($dateFromErrors)) {
                $allErrors = $dateFromErrors;
            } else {
                $dates['dateFrom'] = $dateFromYear . '-' . $dateFromMonth . '-' . $dateFromDay;
            }
        }

        //only if all three fields of a date are empty, we use the default
        if ($dateToDay === null && $dateToMonth === null && $dateToYear === null) {
            $dates['dateTo'] = $currentDateString;
        } else {
            $dateToErrors = $this->processDateParts('Date To', $dateToDay, $dateToMonth, $dateToYear);

            if (!empty($dateToErrors)) {
                $allErrors = array_merge($dateToErrors, $allErrors);
            } else {
                $dates['dateTo'] = $dateToYear . '-' . $dateToMonth . '-' . $dateToDay;
            }
        }

        if (empty($allErrors)) {
            $allErrors = array_merge(
                $this->checkRelatedDates($dates['dateFrom'], $dates['dateTo'], $earliestDateString, $currentDateString),
                $allErrors
            );

            if (empty($allErrors)) {
                $dates['dateFrom'] .= ' 00:00:00';
                $dates['dateTo'] .= ' 23:59:59';

                return $dates;
            }
        }

        $this->addErrorMessages($allErrors);

        return false;
    }

    /**
     * Validates the different parts of the date and returns an array of error
     * messages
     *
     * @param string $fieldName
     * @param int $day
     * @param int $month
     * @param int $year
     * @return array
     */
    private function processDateParts($fieldName, $day, $month, $year)
    {
        $returnErrors = [];

        try {
            DateUtils::validateDateByParts($day, $month, $year);
        } catch (IncorrectDateFormatException $e) {
            $returnErrors[] = $fieldName . ': ' . 'Enter a date in the format dd mm yyyy';
        } catch (DateException $e) {
            $returnErrors[] = $fieldName . ': ' . $e->getMessage();
        }

        return $returnErrors;
    }

    /**
     * Checks dates are within valid boundaries, also in relation to each other
     * such as Date From not being after Date To
     *
     * @param string $strDateFrom
     * @param string $strDateTo
     * @param string $strEarliest
     * @param string $strLatest
     *
     * @return array
     */
    private function checkRelatedDates($strDateFrom, $strDateTo, $strEarliest, $strLatest)
    {
        $errors = [];

        $dateEarliest = DateUtils::toDate($strEarliest);
        $dateLatest = DateUtils::toDate($strLatest);
        $formattedEarliest = $dateEarliest->format('d/m/Y');
        $formattedLatest = $dateLatest->format('d/m/Y');
        $isValidDateFrom = DateUtils::isValidDate($strDateFrom);
        $isValidDateTo = DateUtils::isValidDate($strDateTo);
        $dateFrom = $dateTo = null;

        if (!$isValidDateFrom) {
            $errors[] = 'Date From is not a valid date';
        } else {
            $dateFrom = DateUtils::toDate($strDateFrom);

            if (DateUtils::isDateInFuture($dateFrom)) {
                $errors[] = 'Date From can\'t be in the future';
            }
            if (!DateUtils::isDateTimeBetween($dateFrom, $dateEarliest, $dateLatest)) {
                $errors[] = 'Date From must be between ' . $formattedEarliest . ' and ' . $formattedLatest;
            }
        }

        if (!$isValidDateTo) {
            $errors[] = 'Date To is not a valid date';
        } else {
            $dateTo = DateUtils::toDate($strDateTo);

            if (DateUtils::isDateInFuture($dateTo)) {
                $errors[] = 'Date To can\'t be in the future';
            }

            if (!DateUtils::isDateTimeBetween($dateTo, $dateEarliest, $dateLatest)) {
                $errors[] = 'Date To must be between ' . $formattedEarliest . ' and ' . $formattedLatest;
            }
        }

        if ($isValidDateFrom && $isValidDateTo && $dateFrom > $dateTo
        ) {
            $errors[] = 'Date From can\'t be after Date To';
        }

        return $errors;
    }

    /**
     * Provides dates in a format suitable for searching, based on the range
     * selected. Will return false if a valid range isn't posted, allowing
     * other dates e.g. from a posted form, to be tried instead.
     *
     * @param string $range
     * @param \DateTime $currentDate
     * @return array|bool
     */
    private function getDatesFromRange($range, \DateTime $currentDate)
    {
        switch ($range) {
            case 'today':
                $dateInterval = 'P0D';
                break;
            case '7days':
                $dateInterval = 'P7D';
                break;
            case '30days':
                $dateInterval = 'P30D';
                break;
            case '1year':
                $dateInterval = 'P1Y';
                break;
            default:
                return false;
        }

        $dates['dateTo'] = $currentDate->format('Y-m-d') . ' 23:59:59';
        $dates['dateFrom'] = $currentDate->sub(new \DateInterval($dateInterval))->format('Y-m-d') . ' 00:00:00';

        return $dates;
    }

    /**
     * Builds date search params from the current request, will use a date range
     * if available, otherwise will look for variables from a form
     *
     * @param \Zend\Http\Request $request
     * @param \DateTime $currentDate
     * @return array
     */
    private function buildDateSearchParams(\Zend\Http\Request $request, \DateTime $currentDate)
    {
        $range = (string)$request->getQuery(QueryParam::DATE_RANGE);

        if ($range) {
            $dates = $this->getDatesFromRange($range, $currentDate);
        }

        if (!isset($dates) || !$dates) {
            $dates = $this->getDatesFromRequest($request);
        }

        return $dates;
    }

    /**
     * Removes dates from search parameters, allows parameters to be passed to date
     * range links free of form input fields which would mess up the query string.
     * Also removes other fields which are calculated but then not needed for a query string
     *
     * @param array $searchParams
     * @return array
     */
    private function removeUnusedParams($searchParams)
    {
        foreach ($this->dateFormFields as $field) {
            if (isset($searchParams[$field])) {
                unset($searchParams[$field]);
            }
        }

        if (isset($searchParams['submit'])) {
            unset($searchParams['submit']);
        }

        return $searchParams;
    }

    /**
     * Constants are not allowed in traits, so the form field names are stored here for now
     *
     * @return array
     */
    private function getDateFormFields()
    {
        return QueryParam::getFormDateParams();
    }

    /**
     * Calculates the correct DQL builder start record,
     * based on page number and records per page
     *
     * @param int $currentPage
     * @param int $recordsPerPage
     * @return int
     */
    private function calculateOffset($currentPage, $recordsPerPage)
    {
        if ((int)$currentPage == 0 || (int)$recordsPerPage == 0) {
            return 0;
        }

        return (($currentPage - 1) * $recordsPerPage);
    }

    /**
     * Builds a data table. Accepts the table name (maps to the table config
     * file), data for the table, and a page number for the pagination helper
     *
     * @param string $table
     * @param array $data
     * @param int $page
     * @return \Application\Service\ReportBuilder\Service
     */
    private function buildTable($table, $data, $page)
    {
        $tableBuilder = $this->getServiceLocator()
            ->get(ReportBuilderService::class)
            ->getTable($table);

        return $tableBuilder->setData($data, $page);
    }

    /**
     * Makes a rest call based on the specified path and params
     *
     * Will run the result through a processData function before returning
     *
     * @param string $apiPath
     * @param array $params
     * @return array
     */
    private function getWithParams($apiPath, $params)
    {
        $data = $this->getRestClient()
            ->getWithParams($apiPath, $params)['data'];

        return $this->processData($data);
    }

    /**
     * Override this in individual controllers, if needed
     *
     * @param array $data
     * @return array
     */
    private function processData($data)
    {
        return $data;
    }
}
