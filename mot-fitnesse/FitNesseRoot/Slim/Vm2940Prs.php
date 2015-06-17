<?php
require_once 'configure_autoload.php';

use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class Vm2940Prs
{

    /**
     * @var MotTestHelper $motTestHelper
     */
    private $motTestHelper;
    private $rfrFail;
    private $rfrPrs;
    private $rfrAdvisory;

    private $motTest;
    private $hasPrsMotTest;
    private $prsMotTest;

    private $testerUsername;
    private $siteId;
    private $vehicleId;

    public function __construct($testerUsername, $siteId, $vehicleId)
    {
        $this->testerUsername = $testerUsername;
        $this->siteId = $siteId;
        $this->vehicleId = $vehicleId;
    }

    public function getMotTest($motTestNumber)
    {
        TestShared::executeAndReturnResponseAsArrayFromUrlBuilder(
            $this,
            (new UrlBuilder())->motTestResult()->routeParam('motTestNumber', $motTestNumber)
        );
    }

    public function setRfrFail($value)
    {
        $this->rfrFail = intval($value);
    }

    public function setRfrPrs($value)
    {
        $this->rfrPrs = intval($value);
    }

    public function setRfrAdvisory($value)
    {
        $this->rfrAdvisory = intval($value);
    }

    public function status()
    {
        $this->motTestHelper = new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider($this->testerUsername,
            TestShared::PASSWORD)
        );
        $idHolder = $this->motTestHelper->createMotTest($this->vehicleId, null, $this->siteId, 'C', 'C', true, VehicleClassCode::CLASS_4, 'PE');
        $motTestNumber = $idHolder['motTestNumber'];

        $this->motTestHelper->odometerUpdate($motTestNumber);
        $this->motTestHelper->passBrakeTestResults($motTestNumber);

        $newStatus = 'PASSED';
        if ($this->rfrFail) {
            $this->motTestHelper->addRfr($motTestNumber, $this->rfrFail);
            $newStatus = 'FAILED';
        }
        if ($this->rfrAdvisory) {
            $this->motTestHelper->addRfr($motTestNumber, $this->rfrAdvisory, 'ADVISORY');
        }
        if ($this->rfrPrs) {
            $this->motTestHelper->addRfr($motTestNumber, $this->rfrPrs, 'PRS');
        }

        $this->motTestHelper->changeStatus($motTestNumber, $newStatus);
        $this->motTest = $this->motTestHelper->getMotTest($motTestNumber);

        return $this->motTest['status'];
    }

    public function hasTwinTest()
    {
        $this->hasPrsMotTest = !empty($this->motTest['prsMotTestNumber']);

        return $this->hasPrsMotTest ? "YES" : "NO";
    }

    public function prsMotTestStatus()
    {
        if (!$this->hasPrsMotTest) {
            return "N/A";
        }
        $this->prsMotTest = $this->motTestHelper->getMotTest($this->motTest['prsMotTestNumber']);

        return $this->prsMotTest['status'];
    }

    public function prsAdvisory()
    {
        if (!$this->hasPrsMotTest) {
            return "N/A";
        }
        $advisories = $this->prsMotTest['reasonsForRejection']['ADVISORY'];
        $currentAdvisory = current($advisories);

        return $currentAdvisory['rfrId'];
    }
}
