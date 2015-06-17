<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;
use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\MotTestTypeCode;

class Vm2586MotStatusOtp
{
    private $user;
    private $newStatus;
    private $otp;
    private $sameTest = false;
    private $siteId = 1;
    private $password = TestShared::PASSWORD;
    private $vehicleId;

    private $previousTest;
    /**
     * @var \ApiErrorException $exception
     */
    private $exception;

    private $result;

    public function setUser($value)
    {
        $this->user = $value;
    }

    public function setNewStatus($value)
    {
        $this->newStatus = $value;
    }

    public function setOneTimePassword($value)
    {
        $this->otp = $value;
    }

    public function setUseSameTest($value)
    {
        $this->sameTest = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    public function setInfoAboutMotTest($value)
    {
    }

    public function success()
    {
        $motTestHelper = new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider($this->user, $this->password));

        try {
            if (!$this->sameTest) {

                $this->previousTest = $motTestHelper->createMotTest(
                    $this->vehicleId,
                    null,
                    $this->siteId,
                    ColourCode::ORANGE,
                    ColourCode::BLACK,
                    true,
                    VehicleClassCode::CLASS_4,
                    'PE',
                    'NORMAL',
                    (($this->otp === '') ? MotTestTypeCode::TARGETED_REINSPECTION : MotTestTypeCode::NORMAL_TEST)
                );
            }

            $motTestHelper->odometerUpdate($this->previousTest['motTestNumber']);
            $motTestHelper->passBrakeTestResults($this->previousTest['motTestNumber']);

            $this->result = $motTestHelper->changeStatus(
                $this->previousTest['motTestNumber'], $this->newStatus, $this->otp
            );
            $this->exception = null;

        } catch (ApiErrorException $ex) {
            $this->exception = $ex;
        }

        return $this->isSuccess();
    }

    private function isSuccess()
    {
        var_dump($this->exception);
        return !$this->exception;
    }

    public function errorMessages()
    {
        return $this->isSuccess() ? '' : $this->exception->getMessage();
    }

    public function isLocked()
    {
        return !$this->isSuccess() && $this->exception->getErrorData()['attempts']['left'] === 0;
    }

    public function setVehicleId($vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }
}
