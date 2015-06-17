<?php


class Vm36MotTestUpdateStatus
{
    private $newStatus;
    private $reasonForCancel;
    private $fillIn = false;
    private $addFailure = false;
    private $motTestNumber;
    private $testerUsername;

    private $errorMessage;

    /** @var  MotTestHelper */
    private $motTestHelper;

    public function setTesterUsername($testerUsername)
    {
        $this->testerUsername = $testerUsername;
    }

    public function setMotTestNumber($motTestNumber)
    {
        $this->motTestNumber = $motTestNumber;
    }

    public function setNewStatus($value)
    {
        $this->newStatus = $value;
    }

    public function setReasonForCancel($value)
    {
        $this->reasonForCancel = $value;
    }

    public function setFillInTestResults($value)
    {
        $this->fillIn = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function setAddFailure($value)
    {
        $this->addFailure = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function setInfoAboutMotTest($value)
    {
    }

    public function success()
    {
        $this->errorMessage = '';
        $result = false;

        try {
            $tester = new \MotFitnesse\Util\CredentialsProvider(
                $this->testerUsername,
                \MotFitnesse\Util\TestShared::PASSWORD
            );
            $this->motTestHelper = new MotTestHelper($tester);

            if ($this->fillIn) {
                $this->motTestHelper->odometerUpdate($this->motTestNumber);
                $this->motTestHelper->passBrakeTestResults($this->motTestNumber);
            }

            if ($this->addFailure) {
                $this->motTestHelper->addRfr($this->motTestNumber, 508);
            }

            $this->motTestHelper->changeStatus(
                $this->motTestNumber, $this->newStatus, null, $this->reasonForCancel
            );
            $result = true;
        } catch (ApiErrorException $ex) {
            $this->errorMessage = $ex->getMessage();
        }

        return $result;
    }

    public function errorMessages()
    {
        return $this->errorMessage;
    }
}
