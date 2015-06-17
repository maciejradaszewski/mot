<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\CredentialsProvider;
use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\MotTestStatusName;

class Vm136CompareTestResults
{
    private $siteId;
    private $reinspectionTestNumber;
    private $testerUsername;
    private $testerPassword;
    private $vehicleExaminerUsername;
    private $vehicleExaminerPassword;

    private $testResult;
    private $reinspectionResult;

    private $comparisonResult;

    private $motTestNumber;

    public function __construct()
    {
        $this->reinspectionResult = null;
        $this->testResult = null;
        $this->comparisonResult = null;
    }

    public function execute()
    {
        $tester = new CredentialsProvider($this->testerUsername, $this->testerPassword);
        $enfTester = new CredentialsProvider($this->vehicleExaminerUsername, $this->vehicleExaminerPassword);

        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::createForCreds($tester));
        $vehicleId = $vehicleTestHelper->generateVehicle();

        $motTestHelper = new MotTestHelper($tester);
        $this->motTestNumber = $motTestHelper->createMotTest($vehicleId, null, $this->siteId)['motTestNumber'];
        $motTestHelper->odometerUpdate($this->motTestNumber, 'OK', 12345);
        foreach (
            [
                'FAIL'     => [1551, 971, 1570, 8590, 856, 886, 953, 7448],
                'PRS'      => [8320],
                'ADVISORY' => [2034, 1550, 8589]
            ] as $type => $rfrIds) {
            foreach ($rfrIds as $rfrId) {
                $motTestHelper->addRfr($this->motTestNumber, $rfrId, $type);
            }
        }
        $motTestHelper->passBrakeTestResults($this->motTestNumber);
        $motTestHelper->changeStatus($this->motTestNumber, MotTestStatusName::FAILED, null, null, 'Because');

        $this->testResult = $motTestHelper->getMotTest($this->motTestNumber);

        $motTestHelper = new MotTestHelper($enfTester);
        $this->reinspectionTestNumber = $motTestHelper->createMotTest(
            $vehicleId,
            null,
            $this->siteId,
            ColourCode::ORANGE,
            ColourCode::BLACK,
            true,
            VehicleClassCode::CLASS_4,
            'PE',
            MotTestHelper::TYPE_MOT_TEST_NORMAL,
            'ER',
            $this->motTestNumber
        )['motTestNumber'];
        $motTestHelper->odometerUpdate($this->reinspectionTestNumber, 'OK', 12345);
        foreach (
            [
                'FAIL'     => [1550, 8568, 1569, 8589, 7448],
                'PRS'      => [8325],
                'ADVISORY' => [2034, 2034, 1550, 8589]
            ] as $type => $rfrIds
        ) {
            foreach ($rfrIds as $rfrId) {
                $motTestHelper->addRfr($this->reinspectionTestNumber, $rfrId, $type);
            }
        }
        $motTestHelper->changeStatus($this->reinspectionTestNumber, MotTestStatusName::FAILED, null, null, 'Because');

        $this->reinspectionResult = $motTestHelper->getMotTest($this->reinspectionTestNumber);
    }

    public function motTestNumber()
    {
        return $this->motTestNumber;
    }

    public function veMotTestType()
    {
        if ($this->reinspectionResult) {
            return $this->reinspectionResult['testType']['code'];
        }
        return "no ER data";
    }


    public function ntMotTestType()
    {
        if ($this->testResult) {
            return $this->testResult['testType']['code'];
        }
        return "no NT data";
    }

    public function veMotTestRfrCount()
    {
        return $this->countRfrs($this->reinspectionResult);
    }

    public function ntMotTestRfrCount()
    {
        return $this->countRfrs($this->testResult);
    }

    public function url()
    {
        return $this->_url;
    }

    private function countRfrs($motData)
    {
        if ($motData) {
            $count = 0;
            foreach (array_keys($motData['reasonsForRejection']) as $rfrKey) {
                $count += count($motData['reasonsForRejection'][$rfrKey]);
            }
            return $count;
        }
        return 'no data';
    }

    public function compareRfrCount()
    {
        $this->comparisonResult = $this->pvtLoadMotComparisonData($this->reinspectionTestNumber);

        if ($this->comparisonResult) {
            $count = 0;
            foreach (array_keys($this->comparisonResult) as $entry) {
                $count += count($this->comparisonResult[$entry]);
            }
            return $count;
        }
        return "no data to diff-count";
    }

    private function pvtLoadMotComparisonData($motTestNumber)
    {
        return TestShared::executeAndReturnResponseAsArrayFromUrlBuilder(
            new CredentialsProvider($this->vehicleExaminerUsername, $this->vehicleExaminerPassword),
            (new UrlBuilder())->motTestResultCompare()->routeParam('motTestNumber', $motTestNumber)
        );
    }

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    public function setTesterUsername($testerUsername)
    {
        $this->testerUsername = $testerUsername;
    }

    public function setTesterPassword($testerPassword)
    {
        $this->testerPassword = $testerPassword;
    }

    public function setVehicleExaminerUsername($vehicleExaminerUsername)
    {
        $this->vehicleExaminerUsername = $vehicleExaminerUsername;
    }

    public function setVehicleExaminerPassword($vehicleExaminerPassword)
    {
        $this->vehicleExaminerPassword = $vehicleExaminerPassword;
    }
}
