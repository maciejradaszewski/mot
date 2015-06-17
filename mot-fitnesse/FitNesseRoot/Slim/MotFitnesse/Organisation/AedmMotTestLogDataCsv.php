<?php

require_once 'configure_autoload.php';

use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Search\MotTestSearchParamsDto;
use DvsaCommon\Dto\Search\SearchResultDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Utility\DtoHydrator;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Check api responce for Mot Test Log Csv
 *
 * Class MotFitnesse_Testing_Organisation_AedmMotTestLogDataCsv
 */
class MotFitnesse_Organisation_AedmMotTestLogDataCsv
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;

    /** @var TestSupportHelper */
    private $testSupportHelper;
    /** @var DtoHydrator */
    private $dtoHydrator;

    private $aeId;

    /** @var \DateTime */
    private $dateFrom;
    /** @var \DateTime */
    private $dateTo;
    /** @var SearchResultDto|array */
    private $result;

    public function __construct()
    {
        $this->dtoHydrator = new DtoHydrator();
        $this->testSupportHelper = new TestSupportHelper();

        $this->createRequiredEntities();
    }

    public function setDateFrom($value)
    {
        $this->dateFrom = new \DateTime($value);
    }

    public function setDateTo($value)
    {
        $this->dateTo = new \DateTime($value);
    }

    public function success()
    {
        $this->error = false;
        $this->result = $this->fetchResult();

        if (isset($this->result['error'])) {
            $this->error = true;
            return $this->result['content']['message'];
        }

        $this->result = $this->dtoHydrator->doHydration($this->result['data']);

        return get_class($this->result);
    }

    public function checkRowsCount()
    {
        return ($this->result instanceof SearchResultDto ? $this->result->getTotalResultCount() : null);
    }

    public function checkColumnsCount()
    {
        if (!$this->result instanceof SearchResultDto) {
            return null;
        }

        $data = $this->result->getData();

        return (!empty($data) ? count(current($data)) : 0);
    }

    public function checkColumns()
    {
        if (!$this->result instanceof SearchResultDto) {
            return null;
        }

        $data = $this->result->getData();
        if (empty($data)) {
            return null;
        }

        return implode(', ', array_keys(current($data)));
    }

    protected function fetchResult()
    {
        $searchParams = new MotTestSearchParamsDto();

        $searchParams
            ->setFormat(SearchParamConst::FORMAT_DATA_CSV)
            ->setRowsCount()
            ->setPageNr(1)
            ->setDateFromTS($this->dateFrom->setTime(0, 0, 0)->getTimestamp())
            ->setDateToTS($this->dateTo->setTime(23, 59, 59)->getTimestamp())
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
            ->setIsEsEnabled(false);

        $postData = $this->dtoHydrator->extract($searchParams);

        $searchResult = TestShared::execCurlWithJsonBodyForJsonFromUrlBuilder(
            $this,
            UrlBuilder::motTestLog($this->aeId),
            $postData
        );
        return $searchResult;
    }

    private function createRequiredEntities()
    {
        $date1 = new \DateTime('2014-06-01 13:00:00');
        $date2 = new \DateTime('2014-04-30 13:00:00');

        $testSupportHelper = $this->testSupportHelper;

        // --   Creating required entity for ORG A  --
        $schemaUser = $testSupportHelper->createSchemeManager();
        $userName = $schemaUser['username'];

        $aeA = $testSupportHelper->createAuthorisedExaminer(
            $testSupportHelper->createAreaOffice1User()['username']
        );
        $this->aeId = $aeA['id'];

        $vtsA = $testSupportHelper->createVehicleTestingStation(
            $testSupportHelper->createAreaOffice1User()['username'],
            $this->aeId
        );
        $vtsId = $vtsA['id'];

        $testerA = $testSupportHelper->createTester($userName, [$vtsId]);
        $testerUserName = $testerA['username'];


        // --   Creating mottest    --
        $statuses = [
            MotTestStatusName::PASSED,
            MotTestStatusName::FAILED,
            MotTestStatusName::ABORTED,
            MotTestStatusName::ABANDONED,
        ];

        $excludeTypes = [
            MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST,
            MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING,
        ];

        /** @var \DateTime $date */
        foreach ([$date1, $date2] as $date) {
            foreach ($statuses as $idx => $status) {
                $this->createTest(
                    $testerUserName,
                    $vtsId,
                    $date->add(new DateInterval('PT' . $idx . 'M')),
                    $status,
                    MotTestTypeCode::NORMAL_TEST
                );
            }

            //  --  have to not exists in result    --
            foreach ($excludeTypes as $type) {
                $this->createTest(
                    $testerUserName,
                    $vtsId,
                    $date->add(new DateInterval('PT10M')),
                    MotTestStatusName::PASSED,
                    $type
                );
            }
        }

        // --   ACTIVE TEST --
        $this->createTest(
            $testerUserName,
            $vtsId,
            $date1,
            MotTestStatusName::ACTIVE
        );

    }

    private function createTest($username, $siteId, \DateTime $dateTime, $outcome, $testType = null)
    {
        $dateSet = [
            'startDate'     => DateTimeApiFormat::dateTime($dateTime),
            'issueDate'     => DateTimeApiFormat::date($dateTime),
            'completedDate' => DateTimeApiFormat::dateTime($dateTime),
            'expiryDate'    => DateTimeApiFormat::date($dateTime)
        ];

        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::create($username, TestShared::PASSWORD));
        $vehicleId = $vehicleTestHelper->generateVehicle();

        $this->testSupportHelper->createMotTest($username, $siteId, $vehicleId, $outcome, null, 12345, $dateSet, $testType);
    }

}
 