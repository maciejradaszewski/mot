<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\FtEnfTesterCredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Class Vm1612ReinforcementGet
 */
class Vm1612ReinforcementGet
{
    private $siteId;
    private $enforcementResultId;

    private $result;

    private $originalMotTestNumber;
    private $reinspectionMotTestNumber;

    public function execute()
    {
        $credentials = new FtEnfTesterCredentialsProvider();

        $vehicleTestHelper = new VehicleTestHelper(
            FitMotApiClient::create(TestShared::USERNAME_TESTER1, TestShared::PASSWORD)
        );
        $vehicleId = $vehicleTestHelper->generateVehicle(['vin' => '0NIV0RATF71000LKJ']);

        $motTestHelper = new MotTestHelper($credentials);
        $this->originalMotTestNumber = $motTestHelper->createMotTest(
            $vehicleId,
            null,
            $this->siteId,
            ColourCode::ORANGE,
            ColourCode::BLACK,
            true,
            VehicleClassCode::CLASS_4,
            'PE',
            MotTestHelper::TYPE_MOT_TEST_NORMAL,
            'ER'
        )['motTestNumber'];
        $motTestHelper->odometerUpdate($this->originalMotTestNumber, 'OK', 12345);
        $motTestHelper->addRfr($this->originalMotTestNumber, 8455);
        $motTestHelper->changeStatus($this->originalMotTestNumber, 'FAILED', null, null, 'Because');

        $this->reinspectionMotTestNumber = $motTestHelper->createMotTest(
            $vehicleId,
            null,
            $this->siteId,
            ColourCode::ORANGE,
            ColourCode::BLACK,
            true,
            VehicleClassCode::CLASS_4,
            'PE',
            MotTestHelper::TYPE_MOT_TEST_NORMAL,
            'ER'
        )['motTestNumber'];
        $motTestHelper->odometerUpdate($this->reinspectionMotTestNumber, 'OK', 12345);
        $rfrId = $motTestHelper->addRfr($this->reinspectionMotTestNumber, 8455);
        $motTestHelper->changeStatus($this->reinspectionMotTestNumber, 'FAILED', null, null, 'Because');

        $postResult = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            new FtEnfTesterCredentialsProvider(),
            (new UrlBuilder())->enforcementMotTestResult(),
            [
                'reinspectionMotTestNumber' => $this->reinspectionMotTestNumber,
                'motTestNumber'             => $this->originalMotTestNumber,
                'mappedRfrs'                => [
                    $rfrId => [
                        'score'         => '4',
                        'decision'      => '2',
                        'category'      => '2',
                        'justification' => 'we passed a value',
                        'error'         => '0'
                    ]
                ],
                'caseOutcome'               => '3',
                'record_assessment_button'  => '',
                'finalJustification'        => 'three'
            ]
        );

        $this->enforcementResultId = $postResult['data']['id'];

        $this->result = TestShared::execCurlForJsonFromUrlBuilder(
            new FtEnfTesterCredentialsProvider(),
            (new UrlBuilder())->enforcementMotTestResult()->routeParam('id', $this->enforcementResultId)
        );
    }

    /**
     * @param array $checkItemsExist
     *
     * @return Vm1612ReinforcementGet
     */
    public function setCheckItemsExist($checkItemsExist)
    {
        $this->checkItemsExist = $checkItemsExist;
        return $this;
    }

    /**
     * @return null
     */
    public function totalScore()
    {
        return $this->enforcementResult()['totalScore'];
    }

    /**
     * @return null
     */
    public function createdOn()
    {
        return $this->enforcementResult()['createdOn'];
    }

    /**
     * @return null
     */
    public function lastUpdatedOn()
    {
        return $this->enforcementResult()['lastUpdatedOn'];
    }

    /**
     * @return null
     */
    public function version()
    {
        return $this->enforcementResult()['version'];
    }

    /**
     * @return null
     */
    public function decisionOutcome()
    {
        return $this->enforcementResult()['decisionOutcome']['outcome'];
    }

    /**
     * @return null
     */
    public function comment()
    {
        return $this->enforcementResult()['comment']['comment'];
    }

    /**
     * @return null
     */
    public function createdBy()
    {
        return $this->enforcementResult()['createdBy'];
    }

    /**
     * @return null
     */
    public function createdPasswordExists()
    {
        return is_scalar($this->enforcementResult()['createdBy']) ? 'no' : 'maybe';
    }

    /**
     * @return null
     */
    public function lastUpdatedBy()
    {
        return $this->enforcementResult()['lastUpdatedBy'];
    }

    /**
     * @return null
     */
    public function lastUpdatedPasswordExists()
    {
        return isset($this->result['data']['lastUpdatedBy']['password']) ? 'yes' : 'no';
    }

    /**
     * @return null
     */
    public function odometerValue()
    {
        return $this->originalMotTest()['odometerReading']['value'];
    }

    /**
     * @return null
     */
    public function status()
    {
        return $this->originalMotTest()['status'];
    }

    /**
     * @return null
     */
    public function vin()
    {
        return $this->originalMotTest()['vehicle']['vin'];
    }

    /**
     * @return null
     */
    public function vehicleTestingStation()
    {
        return $this->originalMotTest()['vehicleTestingStation']['name'];
    }

    /**
     * @return null
     */
    public function testType()
    {
        return $this->result['data']['testDifferences'][0]['motTestType'];
    }

    /**
     * @return null
     */
    public function reasonForRejection()
    {
        return $this->originalMotTest()['reasonsForRejection']['FAIL'][0]['inspectionManualReference'];
    }

    /**
     * @return mixed
     */
    private function enforcementResult()
    {
        return $this->result['data']['enforcementResult'];
    }

    /**
     * @return mixed
     */
    private function originalMotTest()
    {
        return $this->result['data']['motTests'][$this->originalMotTestNumber];
    }

    public function reinspectionTestNumber()
    {
        return $this->reinspectionMotTestNumber;
    }

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }
}
