<?php
require_once 'configure_autoload.php';

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class MotFitnesse_Organisation_AedmMotTestLogSummary
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;

    private $testSupportHelper;

    private $result;
    private $error;

    private $schemaA;
    private $aeA;
    private $vtsA;
    private $testerA;

    public function __construct()
    {
        $this->createRequiredEntities();
    }

    private function getTestSupportHelper()
    {
        if (is_null($this->testSupportHelper)) {
            $this->testSupportHelper = new TestSupportHelper();
        }

        return $this->testSupportHelper;
    }

    private function createTest($username, $siteId, \DateTime $dateTime, $outcome, $testType = null)
    {

        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::create($username, TestShared::PASSWORD));
        $vehicleId = $vehicleTestHelper->generateVehicle();

        $this->getTestSupportHelper()->createMotTest(
            $username,
            $siteId,
            $vehicleId,
            $outcome,
            null,
            12345,
            [
                'startDate'     => DateTimeApiFormat::dateTime($dateTime),
                'issueDate'     => DateTimeApiFormat::date($dateTime),
                'completedDate' => DateTimeApiFormat::dateTime($dateTime),
                'expiryDate'    => DateTimeApiFormat::date($dateTime)
            ],
            $testType
        );
    }

    private function createRequiredEntities()
    {
        $dateCurr = new \DateTime();
        $date1m = DateUtils::subtractCalendarMonths(new \DateTime(), '1');
        $date6m = DateUtils::subtractCalendarMonths(new \DateTime(), '6');
        $date13m = DateUtils::subtractCalendarMonths(new \DateTime(), '13');

        // Creating required entity for ORG A
        $this->schemaA = $this->getTestSupportHelper()->createSchemeManager();
        $this->aeA = $this->getTestSupportHelper()->createAuthorisedExaminer(
            $this->getTestSupportHelper()->createAreaOffice1User()['username']
        );
        $this->vtsA = $this->getTestSupportHelper()->createVehicleTestingStation(
            $this->getTestSupportHelper()->createAreaOffice1User()['username'],
            $this->aeA['id']
        );
        $this->testerA = $this->getTestSupportHelper()->createTester(
            $this->schemaA['username'],
            [$this->vtsA['id']]
        );

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

        foreach ([$dateCurr, $date1m, $date6m, $date13m] as $date) {
            foreach ($statuses as $status) {
                $this->createTest(
                    $this->testerA['username'],
                    $this->vtsA['id'],
                    $date,
                    $status,
                    MotTestTypeCode::NORMAL_TEST
                );
            }

            //  --  have to not exists in result    --
            foreach ($excludeTypes as $type) {
                $this->createTest(
                    $this->testerA['username'],
                    $this->vtsA['id'],
                    $date,
                    MotTestStatusName::PASSED,
                    $type
                );
            }
        }

        /* ACTIVE TEST */
        $this->createTest(
            $this->testerA['username'],
            $this->vtsA['id'],
            $dateCurr,
            MotTestStatusName::ACTIVE
        );
    }

    protected function fetchSearchResult()
    {
        $searchResult = TestShared::execCurlForJsonFromUrlBuilder(
            $this,
            UrlBuilder::motTestLogSummary($this->aeA['id'])
        );
        return $searchResult;
    }

    public function success()
    {
        $this->error = false;
        $this->result = $this->fetchSearchResult();

        if (isset($this->result['error'])) {
            $this->error = true;
            return $this->result['content']['message'];
        }
        return $this->result['data']['_class'];
    }

    public function year()
    {
        if (!$this->error) {
            return $this->result['data']['year'];
        }
        return false;
    }

    public function month()
    {
        if (!$this->error) {
            return $this->result['data']['month'];
        }
        return false;
    }

    public function day()
    {
        if (!$this->error) {
            return $this->result['data']['today'];
        }
        return false;
    }
}
