<?php

require_once 'configure_autoload.php';

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\MotTestStatusName;
use MotFitnesse\Testing\ReplacementCertificateHelper;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\VehicleUrlBuilder;

class ReplacementCertificateIssueWithinSevenDaysForTester
{
    private $username = 'tester1';
    private $password = TestShared::PASSWORD;
    private $vehicleId;

    /** @var \TestSupportHelper */
    private $testSupportHelper;

    /** @var MotTestHelper */
    private $motTestHelper;

    /** @var  ReplacementCertificateHelper */
    private $replacementCertificateHelper;

    private $testerUsername;
    private $testerPassword;
    private $dateOfTest;
    private $motTestNumber;
    private $draftId;

    public function __construct()
    {
        $this->motTestHelper = new MotTestHelper();
        $this->testSupportHelper = new TestSupportHelper();
    }

    public function beginTable()
    {
        $this->setupTester();
        $this->replacementCertificateHelper
            = new ReplacementCertificateHelper($this->testerUsername, $this->testerPassword);
    }

    public function execute()
    {
        $this->setupVehicle();
        $this->setupPassedTest();
        $this->setupReplacementDraft();
    }

    public function setDateOfTest($value)
    {
        $this->dateOfTest = $value;
    }

    public function canTesterIssueReplacement()
    {
        $url = VehicleUrlBuilder::vehicle($this->vehicleId)->testHistory()->toString();
        $data = TestShared::get($url, $this->testerUsername, $this->testerPassword);

        $allowEdit = $data[0]['allowEdit'];

        return $allowEdit;
    }

    public function canApplyDraft()
    {
        $result = $this->replacementCertificateHelper->apply($this->draftId, '123456');

        return TestShared::resultIsSuccess($result);
    }

    private function setupVehicle()
    {
        $vehicleTestHelper = (new VehicleTestHelper(FitMotApiClient::create($this->username, $this->password)));
        $this->vehicleId = $vehicleTestHelper->generateVehicle();
    }

    private function setupPassedTest()
    {
        $response = $this->testSupportHelper->createMotTest(
            $this->testerUsername,
            1,
            $this->vehicleId,
            MotTestStatusName::PASSED,
            null,
            12345,
            [
                'startDate' => DateTimeApiFormat::dateTime(DateUtils::toDate($this->dateOfTest)),
                'issueDate' => DateTimeApiFormat::date(DateUtils::toDate($this->dateOfTest)),
                'completedDate' => DateTimeApiFormat::dateTime(DateUtils::toDate($this->dateOfTest)),
                'expiryDate' => DateTimeApiFormat::date(DateUtils::toDate($this->dateOfTest)->modify('+1 year'))
            ]
        );

        $this->motTestNumber = $response['motTestNumber'];
    }

    private function setupTester()
    {
        $schememgt = $this->testSupportHelper->createSchemeManager();
        $tester = $this->testSupportHelper->createTester($schememgt['username'], [1]);
        $this->testerUsername = $tester['username'];
        $this->testerPassword = $tester['password'];
    }

    private function setupReplacementDraft()
    {
        $result = $this->replacementCertificateHelper->create($this->motTestNumber);
        $this->draftId = (int)$result['data']['id'];
    }
}
