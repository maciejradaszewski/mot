<?php
use MotFitnesse\Util\TestShared;

class Vm327MotTestUpdateOdometer
{
    private $motTestNumber;
    private $odometerValue;
    private $odometerUnit;
    private $tester;

    /**
     * @var \ApiErrorException $exception
     */
    private $exception;

    public function setMotTestNumber($value)
    {
        $this->motTestNumber = $value;
    }

    public function setOdometerValue($value)
    {
        $this->odometerValue = $value;
    }

    public function setOdometerUnit($value)
    {
        $this->odometerUnit = $value;
    }

    public function setTester($value)
    {
        $this->tester = $value;
    }

    public function setComment($v)
    {
    }

    public function success()
    {
        $result = false;

        $motHelper = new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider($this->tester, TestShared::PASSWORD));
        try {
            $motHelper->odometerUpdate($this->motTestNumber, 'OK', (int)$this->odometerValue, $this->odometerUnit);
            $this->exception = null;
            $result = true;
        } catch (ApiErrorException $ex) {
            $this->exception = $ex;
        }

        return $result;
    }

    public function errorMessages()
    {
        return $this->exception ? $this->exception->getMessage() : '';
    }
}
