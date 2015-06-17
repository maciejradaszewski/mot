<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\ReasonForRejectionTypeName;
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;

class Vm3363RemoveOriginalTestPrsRfrsFromRetest
{
    private $username = TestShared::USERNAME_TESTER1;
    private $password = TestShared::PASSWORD;
    private $siteId = 1;

    private $origFailCount;
    private $origPrsCount;
    private $origAdvisoryCount;
    private $retestFailCount;
    private $retestPrsCount;
    private $retestAdvisoryCount;

    public function setTesterUsername($v)
    {
        $this->username = $v;
    }

    public function setSiteId($v)
    {
        $this->siteId = $v;
    }

    public function errorMessage()
    {
        $credentialProvider = new \MotFitnesse\Util\CredentialsProvider($this->username, $this->password);
        $motHelper = new MotTestHelper($credentialProvider);
        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::createForCreds($credentialProvider));

        try {
            //Create original test
            $vehicleId = $vehicleTestHelper->generateVehicle();

            $motTestNumber = $motHelper->createMotTest($vehicleId, null, $this->siteId)['motTestNumber'];

            $this->addRfrs($motHelper, $motTestNumber, $this->origFailCount, ReasonForRejectionTypeName::FAIL);
            $this->addRfrs($motHelper, $motTestNumber, $this->origPrsCount, ReasonForRejectionTypeName::PRS);
            $this->addRfrs($motHelper, $motTestNumber, $this->origAdvisoryCount, ReasonForRejectionTypeName::ADVISORY);

            $motHelper->odometerUpdate($motTestNumber);
            $motHelper->passBrakeTestResults($motTestNumber);
            $motHelper->changeStatus($motTestNumber, MotTestStatusName::FAILED);

            //Create retest
            $retestId = $motHelper->createMotTest(
                $vehicleId,
                null,
                $this->siteId,
                "B",
                "B",
                true,
                VehicleClassCode::CLASS_4,
                'PE',
                MotTestHelper::TYPE_MOT_TEST_RETEST
            )['motTestNumber'];

            $rfrs = $motHelper->getMotTest($retestId)['reasonsForRejection'];

            $this->retestFailCount = isset($rfrs['FAIL']) ? count($rfrs['FAIL']) : 0;
            $this->retestPrsCount = isset($rfrs['PRS']) ? count($rfrs['PRS']) : 0;
            $this->retestAdvisoryCount = isset($rfrs['ADVISORY']) ? count($rfrs['ADVISORY']) : 0;

            $motHelper->abortTest($retestId);

        } catch (ApiErrorException $ex) {
            return $ex->getMessage();
        }
        return '';
    }

    public function setOrigFailCount($value)
    {
        $this->origFailCount = $value;
    }

    public function setOrigPrsCount($value)
    {
        $this->origPrsCount = $value;
    }

    public function setOrigAdvisoryCount($value)
    {
        $this->origAdvisoryCount = $value;
    }

    public function retestFailCount()
    {
        return $this->retestFailCount;
    }

    public function retestPrsCount()
    {
        return $this->retestPrsCount;
    }

    public function retestAdvisoryCount()
    {
        return $this->retestAdvisoryCount;
    }

    /**
     * @param MotTestHelper $motHelper
     * @param               $motTestNumber
     * @param               $count
     * @param               $type
     */
    protected function addRfrs(MotTestHelper $motHelper, $motTestNumber, $count, $type)
    {
        $i = 0;
        while ($i < $count) {
            $motHelper->addRfr($motTestNumber, 508, $type);
            $i++;
        }
    }
}
