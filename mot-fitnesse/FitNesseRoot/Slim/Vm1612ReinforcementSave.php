<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\FtEnfTesterCredentialsProvider;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\MotTestStatusName;

class Vm1612ReinforcementSave
{
    public $username = null;
    public $password = TestShared::PASSWORD;
    private $motTestNumber;
    private $reinspectionMotTest = null;
    private $mappedRfrs = null;
    private $caseOutcome = null;
    private $finalJustification = null;
    private $result = null;
    private $rfrId;
    private $siteId;
    private $rfrs;

    public function createData()
    {
        $credentials = new FtEnfTesterCredentialsProvider();

        $vehicleTestHelper = new VehicleTestHelper(
            FitMotApiClient::create(TestShared::USERNAME_TESTER1, TestShared::PASSWORD)
        );
        $vehicleId = $vehicleTestHelper->generateVehicle(['vin' => '0NIV0RATF71000LKJ']);

        $motTestHelper = new MotTestHelper($credentials);

        $this->reinspectionMotTest = $motTestHelper->createMotTest(
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
        $motTestHelper->odometerUpdate($this->reinspectionMotTest, 'OK', 12345);
        $this->rfrId = $motTestHelper->addRfr($this->reinspectionMotTest, 8455);
        $motTestHelper->changeStatus($this->reinspectionMotTest, MotTestStatusName::FAILED, null, null, 'Because');
        $this->mappedRfrs = [];

        // process Rfrs
        if (!empty($this->rfrs)) {
            $values = explode(',', $this->rfrs);
            $this->mappedRfrs[$this->rfrId] = [
                "rfrId"         => $values[0],
                "score"         => $values[1],
                "decision"      => $values[2],
                "category"      => $values[3],
                "justification" => $values[4]
            ];
        }

    }

    protected function postData()
    {
        $postArray = [
            'motTestNumber'             => $this->motTestNumber,
            'reinspectionMotTestNumber' => $this->reinspectionMotTest,
            'mappedRfrs'                => $this->getMappedRfrs(),
            'caseOutcome'               => $this->caseOutcome,
            'record_assessment_button'  => '',
            'finalJustification'        => $this->finalJustification
        ];

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $this,
            (new UrlBuilder())->enforcementMotTestResult(),
            $postArray
        );
    }

    public function motTestNumber()
    {
        $this->createData();
        return $this->motTestNumber;
    }

    public function setMotTestNumber($motTest)
    {
        $this->motTestNumber = $motTest;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    /**
     * @param null $caseOutcome
     *
     * @return Vm1612ReinforcementSave
     */
    public function setCaseOutcome($caseOutcome)
    {
        $this->caseOutcome = $caseOutcome;

        return $this;
    }

    /**
     * @return null
     */
    public function getCaseOutcome()
    {
        return $this->caseOutcome;
    }

    /**
     * @param null $finalJustification
     *
     * @return Vm1612ReinforcementSave
     */
    public function setFinalJustification($finalJustification)
    {
        $this->finalJustification = $finalJustification;

        return $this;
    }

    /**
     * @return null
     */
    public function getFinalJustification()
    {
        return $this->finalJustification;
    }

    /**
     * @return int
     */
    public function id()
    {
        $this->postData();

        return isset($this->result['data']) ? $this->result['data']['id'] : null;
    }

    /**
     * @param string $password
     *
     * @return Vm1612ReinforcementSave
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param null $reinspectionMotTest
     *
     * @return Vm1612ReinforcementSave
     */
    public function setReinspectionMotTest($reinspectionMotTest)
    {
        $this->reinspectionMotTest = $reinspectionMotTest;

        return $this;
    }

    /**
     * @return null
     */
    public function getReinspectionMotTest()
    {
        return $this->reinspectionMotTest;
    }

    /**
     * @param null $result
     *
     * @return Vm1612ReinforcementSave
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param array $rfrs
     *
     * @return Vm1612ReinforcementSave
     */
    public function setMappedRfrsRfrIdScoreDecisionCategoryJustification($rfrs)
    {
        $this->rfrs = $rfrs;
    }

    /**
     * @return array
     */
    public function getMappedRfrs()
    {
        return $this->mappedRfrs;
    }

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }
}
