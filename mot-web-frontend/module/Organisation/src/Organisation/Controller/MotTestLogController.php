<?php

namespace Organisation\Controller;

use Core\Controller\AbstractAuthActionController;
use Core\Service\MotFrontendAuthorisationServiceInterface;
use DvsaClient\MapperFactory;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\Organisation\MotTestLogSummaryDto;
use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Dto\Search\SearchResultDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use Organisation\ViewModel\MotTestLog\MotTestLogFormViewModel;
use Organisation\ViewModel\MotTestLog\MotTestLogViewModel;
use Zend\View\Model\ViewModel;

/**
 * Class MotTestLogController
 *
 * @package Organisation\Controller
 */
class MotTestLogController extends AbstractAuthActionController
{
    const TABLE_ROWS_COUNT = 10;
    const MAX_TESTS_COUNT = 50000;

    const DEF_SORT_BY = 'testDateTime';
    const DEF_SORT_DIRECTION = SearchParamConst::SORT_DIRECTION_DESC;

    private static $DATETIME_FORMAT = 'd/m/Y H:i:s';
    private static $DATETIME_FORMAT_EMERG = 'd/m/Y';

    public static $CSV_COLUMNS
        = [
            'Site Number',
            'Client IP',
            'Test date/time',
            'Test Number',
            'Registration',
            'VIN',
            'Make',
            'Model',
            'Class',
            'User Id',
            'Test type',
            'Result',
            'Test Duration',
            'Tester who recorded test',
            'Date/time of recording CT test',
            'Contingency Test Reason',
            'Contingency Code',
        ];

    const ERR_NO_DATA = 'There are no test logs for the selected date range. Please select a wider date range.';

    /**
     * @var MotFrontendAuthorisationServiceInterface
     */
    private $authService;
    /**
     * @var  \Zend\Http\Request
     */
    protected $request;

    public function __construct(
        MotFrontendAuthorisationServiceInterface $authService,
        MapperFactory $mapperFactory
    ) {
        $this->authService = $authService;
        $this->mapperFactory = $mapperFactory;
    }

    public function indexAction()
    {
        $organisationId = $this->params()->fromRoute('id');

        if (!$this->authService->isGrantedAtOrganisation(
            PermissionAtOrganisation::AE_TEST_LOG, $organisationId
        )) {
            return $this->redirect()->toUrl(PersonUrlBuilderWeb::home());
        }

        $this->request = $this->getRequest();

        //  logical block :: get auth examiner and summary data
        $organisation = $this->getAuthorisedExaminer($organisationId);
        $motTestLogs = $this->getLogSummary($organisationId);

        //  logical block :: prepare models for view
        $viewModel = new MotTestLogViewModel($organisation, $motTestLogs);
        $viewModel->parseData($this->request->getQuery());

        $formModel = $viewModel->getFormModel();
        if ($formModel->isValid()) {
            //  logical block :: create object with parameters for sending to api   --
            $searchParams = $this->prepareSearchParams($formModel);
            if ($searchParams->getRowsCount() === 0) {
                $searchParams->setRowsCount($viewModel->getTable()->getTableOptions()->getItemsPerPage());
            }

            //  logical block :: request total count of records(mot tests in log)
            $apiResult = $this->getLogDataBySearchCriteria($organisationId, $searchParams);

            $totalRecordsCount = (int) $apiResult->getTotalResultCount();
            if ($totalRecordsCount === 0) {
                $this->addErrorMessages(self::ERR_NO_DATA);
            }

            //  logical block :: set search parameters and date to table
            $viewModel->getTable()
                ->setSearchParams($apiResult->getSearched())
                ->setRowsTotalCount($apiResult->getTotalResultCount())
                ->setData($apiResult->getData());

            //  logical block :: set search parameters to date range
            $viewModel->getFilterBuilder()
                ->setQueryParams($apiResult->getSearched()->toQueryParams());
        }

        //  logic block: prepare view
        $this->layout('layout/layout-govuk.phtml');

        $breadcrumbs = [
            $organisation->getName() => AuthorisedExaminerUrlBuilderWeb::of($organisationId),
            'Test logs' => '',
        ];
        $this->layout()->setVariable('breadcrumbs', ['breadcrumbs' => $breadcrumbs]);

        $this->layout()->setVariable('pageTitle', $viewModel->getOrganisation()->getName());
        $this->layout()->setVariable('pageSubTitle', 'Test logs of Authorised Examiner');

        return new ViewModel(
            [
                'viewModel' => $viewModel,
            ]
        );
    }

    public function downloadCsvAction()
    {
        $organisationId = $this->params()->fromRoute('id');

        if (!$this->authService->isGrantedAtOrganisation(
            PermissionAtOrganisation::AE_TEST_LOG, $organisationId
        )) {
            return $this->redirect()->toUrl(PersonUrlBuilderWeb::home());
        }

        //  --  create object with parameters for sending to api   --
        $searchParams = $this->prepareSearchParams();
        $searchParams
            ->setFormat(SearchParamConst::FORMAT_DATA_CSV)
            ->setRowsCount(self::MAX_TESTS_COUNT)
            ->setIsApiGetTotalCount(false)
            ->setIsApiGetData(true);

        $apiResult = $this->getLogDataBySearchCriteria($organisationId, $searchParams);

        //  --  define content of csv file  --
        if ($apiResult->getResultCount() > 0) {
            $csvBody = $this->prepareCsvBody($apiResult->getData());
        } else {
            $csvBody = '';
        }

        //  --  define csv file name     --
        $fileName = 'test-log-' .
            (new \DateTime('@' . $searchParams->getDateFromTs()))->format('dmY') . '-' .
            (new \DateTime('@' . $searchParams->getDateToTs()))->format('dmY') . '.csv';

        //  --  set response    --
        /** @var \Zend\Http\Response $response */
        $response = $this->getResponse();

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'text/csv; charset=utf-8')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->addHeaderLine('Accept-Ranges', 'bytes')
            ->addHeaderLine('Content-Length', strlen($csvBody))
            ->addHeaderLine('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->addHeaderLine('Pragma', 'no-cache');

        $response->setContent($csvBody);

        return $response;
    }

    protected function prepareCsvBody($testsData)
    {
        $csvBuffer = fopen('php://memory', 'w');

        if (!empty(self::$CSV_COLUMNS)) {
            fputcsv($csvBuffer, self::$CSV_COLUMNS);
        }

        foreach ($testsData as $row) {
            //  --  date time format for emergency test --
            $testDateFormat = (empty($row['emCode']) ? self::$DATETIME_FORMAT : self::$DATETIME_FORMAT_EMERG);

            //  --  convert to local time zone  --
            $row['testDateTime'] = DateUtils::toUserTz(new \DateTime($row['testDateTime']))->format($testDateFormat);

            $emRecDateTime = $row['emRecDateTime'];
            if ($emRecDateTime !== null) {
                $row['emRecDateTime'] = DateUtils::toUserTz(new \DateTime($emRecDateTime))
                    ->format(self::$DATETIME_FORMAT);
            }

            //="formula" hack preventing Excel from converting columns to Date or Number
            $row['vehicleModel'] = '="' . $row['vehicleModel'] . '"';
            $row['testNumber'] = '="' . $row['testNumber'] . '"';

            // VIN must use ="<vin>" to prevent Excel truncating numeric VIN longer than 15 digits
            $row['vehicleVIN'] = '="' . $row['vehicleVIN'] . '"';

            fputcsv($csvBuffer, $row);
        }

        rewind($csvBuffer);
        $output = stream_get_contents($csvBuffer);

        fclose($csvBuffer);

        return $output;
    }

    /**
     * @return SearchResultDto|null
     */
    private function getLogDataBySearchCriteria($orgId, MotTestSearchParamsDto $searchParams)
    {
        try {
            return $this->mapperFactory->MotTestLog->getData($orgId, $searchParams);
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }

        return null;
    }

    /**
     * @return \DvsaCommon\Dto\Organisation\OrganisationDto|null
     */
    protected function getAuthorisedExaminer($organisationId)
    {
        try {
            return $this->mapperFactory->Organisation->getAuthorisedExaminer($organisationId);
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }

        return null;
    }

    /**
     * Get mot tests log summary information from api (year, prev month, prev week, today)
     *
     * @param int $organisationId
     *
     * @return MotTestLogSummaryDto|null
     */
    protected function getLogSummary($organisationId)
    {
        try {
            return $this->mapperFactory->MotTestLog->getSummary($organisationId);
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }

        return null;
    }

    protected function prepareSearchParams(MotTestLogFormViewModel $formModel = null)
    {
        $queryParams = $this->request->getQuery();

        $dto = new MotTestSearchParamsDto();
        $dto
            ->setFormat(SearchParamConst::FORMAT_DATA_TABLES)
            ->setStatus(
                [
                    MotTestStatusName::ABANDONED,
                    MotTestStatusName::ABORTED,
                    MotTestStatusName::ABORTED_VE,
                    MotTestStatusName::FAILED,
                    MotTestStatusName::PASSED,
                    MotTestStatusName::REFUSED,
                ]
            )
            ->setTestType(
                [
                    MotTestTypeCode::NORMAL_TEST,
                    MotTestTypeCode::PARTIAL_RETEST_LEFT_VTS,
                    MotTestTypeCode::PARTIAL_RETEST_REPAIRED_AT_VTS,
                    MotTestTypeCode::RE_TEST,
                ]
            )
            ->setRowsCount($queryParams->get(SearchParamConst::ROW_COUNT, 0))
            ->setPageNr($queryParams->get(SearchParamConst::PAGE_NR, 1))
            ->setSortBy($queryParams->get(SearchParamConst::SORT_BY, self::DEF_SORT_BY))
            ->setSortDirection($queryParams->get(SearchParamConst::SORT_DIRECTION, self::DEF_SORT_DIRECTION));

        //  logical block: set filter parameters   --
        if ($formModel !== null) {
            $dateFrom = $formModel->getDateFrom()->getDate()->getTimestamp();
            $dateTo = $formModel->getDateTo()->getDate()->getTimestamp();
        } else {
            $dateFrom = $queryParams->get(SearchParamConst::SEARCH_DATE_FROM_QUERY_PARAM);
            $dateTo = $queryParams->get(SearchParamConst::SEARCH_DATE_TO_QUERY_PARAM);
        }

        $dto
            ->setDateFromTs($dateFrom)
            ->setDateToTs($dateTo);

        return $dto;
    }
}
