<?php

namespace Organisation\Controller;

use Core\Controller\AbstractAuthActionController;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\Organisation\MotTestLogSummaryDto;
use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Dto\Search\SearchResultDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\HttpRestJson\Exception\RestApplicationException;
use DvsaCommon\Messages\DateErrors;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;
use DvsaCommon\Utility\DtoHydrator;
use Organisation\Traits\OrganisationServicesTrait;
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

    private static $DATETIME_FORMAT = 'd/m/Y H:i:s';
    private static $DATETIME_FORMAT_EMERG = 'd/m/Y';

    public static $CSV_COLUMNS
        = [
            'Site Number',
            'Client IP',
            'Test date/time',
            'Registration',
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
    const ERR_TOO_MANY_RECORDS = 'Your requested report would contain %1$s test logs. The limit is %2$s. Please shorten the date range.';

    use OrganisationServicesTrait;

    public function indexAction()
    {
        $organisationId = $this->params()->fromRoute('id');

        if (!$this->getAuthorizationService()->isGrantedAtOrganisation(
            PermissionAtOrganisation::AE_TEST_LOG, $organisationId
        )) {
            return $this->redirect()->toUrl(PersonUrlBuilderWeb::home());
        }

        //  --  get auth examiner and summary data  --
        $organisation = $this->getAuthorisedExaminer($organisationId);
        $motTestLogs = $this->getLogSummary($organisationId);

        //  --  prepare models for view --
        $formModel = new MotTestLogFormViewModel();
        $viewModel = new MotTestLogViewModel($organisation, $motTestLogs, $formModel);

        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        //  --  process post    --
        $formData = $request->getQuery()->toArray();

        if (isset($formData['_csrf_token'])) {
            //  --  form model for view   --
            $formModel->parseData($formData);

            //  --  date and range validation   --
            $isValid = $this->validateDatesAndRange($formModel);

            if ($isValid) {
                //  --  create object with parameters for sending to api   --
                $searchParams = $this->prepareSearchParams($formModel);
                $searchParams->setIsApiGetData(false);

                //  --  request total count of records(mot tests in log)    --
                $apiResult = $this->getLogDataBySearchCriteria($organisationId, $searchParams);

                $totalRecordsCount = (int) $apiResult->getTotalResultCount();
                if ($totalRecordsCount === 0) {
                    $this->addErrorMessages(self::ERR_NO_DATA);
                } elseif ($totalRecordsCount > self::MAX_TESTS_COUNT) {
                    $this->addErrorMessages(
                        sprintf(self::ERR_TOO_MANY_RECORDS, $totalRecordsCount, self::MAX_TESTS_COUNT)
                    );
                } else {
                    //  --  request data for csv and output file to browser --
                    return $this->downloadCsv($organisationId, $searchParams);
                }
            }
        }

        return new ViewModel(
            [
                'viewModel' => $viewModel,
            ]
        );
    }

    private function downloadCsv($orgId, MotTestSearchParamsDto $searchParams)
    {
        $searchParams
            ->setFormat(SearchParamConst::FORMAT_DATA_CSV)
            ->setRowsCount()
            ->setIsApiGetTotalCount(false)
            ->setIsApiGetData(true);

        $apiResult = $this->getLogDataBySearchCriteria($orgId, $searchParams);

        //  --  create content of csv  --
        $csvBody = $this->prepareCsvBody($apiResult->getData());

        //  --  define csv file name     --
        $fileName = 'test-log-' .
            (new \DateTime('@' . $searchParams->getDateFromTS()))->format('dmY') . '-' .
            (new \DateTime('@' . $searchParams->getDateToTS()))->format('dmY') . '.csv';

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

    private function prepareCsvBody($testsData)
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
            $row['vehicleModel'] = '="'.$row['vehicleModel'].'"';

            fputcsv($csvBuffer, $row);
        }

        rewind($csvBuffer);
        $output = stream_get_contents($csvBuffer);

        fclose($csvBuffer);

        return $output;
    }

    /**
     * @param MotTestSearchParamsDto $searchParams
     *
     * @return SearchResultDto|null
     */
    private function getLogDataBySearchCriteria($orgId, MotTestSearchParamsDto $searchParams)
    {
        //  --  create object with parameters for sending to api   --
        try {
            $apiUrl = AuthorisedExaminerUrlBuilder::motTestLog($orgId)->toString();
            $apiResult = $this->getRestClient()->post(
                $apiUrl,
                DtoHydrator::dtoToJson($searchParams)
            );

            return DtoHydrator::jsonToDto($apiResult['data']);
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
            return $this->getMapperFactory()->Organisation->getAuthorisedExaminer($organisationId);
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
            $apiUrl = AuthorisedExaminerUrlBuilder::motTestLogSummary($organisationId);
            $apiResult = $this->getRestClient()->get($apiUrl);

            return DtoHydrator::jsonToDto($apiResult['data']);
        } catch (RestApplicationException $e) {
            $this->addErrorMessages($e->getDisplayMessages());
        }

        return null;
    }

    private function validateDatesAndRange(MotTestLogFormViewModel $formModel)
    {
        $dateFrom = $formModel->getDateFrom()->getDate();
        $dateTo = $formModel->getDateTo()->getDate();

        $this->validateDate($dateFrom, 'From');
        $this->validateDate($dateTo, 'To');

        if ($dateFrom && $dateTo && $dateFrom > $dateTo) {
            $this->addErrorMessages(sprintf(DateErrors::INCORRECT_INTERVAL, 'To', 'From'));
        }

        return count($this->flashMessenger()->getCurrentErrorMessages()) === 0;
    }

    private function validateDate($date, $fieldSfx)
    {
        if ($date === null) {
            $this->addErrorMessages(sprintf(DateErrors::DATE_INVALID, $fieldSfx));
        } elseif (DateUtils::isDateInFuture($date)) {
            $this->addErrorMessages(sprintf(DateErrors::DATE_FUTURE, $fieldSfx));
        }
    }

    private function prepareSearchParams(MotTestLogFormViewModel $formModel)
    {
        $dto = new MotTestSearchParamsDto();

        $dto
            ->setFormat(SearchParamConst::FORMAT_DATA_TABLES)
            ->setRowsCount(self::TABLE_ROWS_COUNT)
            ->setPageNr(1)
            ->setDateFromTS($formModel->getDateFrom()->getDate()->setTime(0, 0, 0)->getTimestamp())
            ->setDateToTS($formModel->getDateTo()->getDate()->setTime(23, 59, 59)->getTimestamp())
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
            );

        return $dto;
    }
}
