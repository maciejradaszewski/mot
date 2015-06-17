<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\TestShared;

/**
 * Class Vm2948AbortMotTestInProgress
 */
class Vm2948AbortMotTestInProgress
{

    private $username;
    private $password = TestShared::PASSWORD;

    private $motTestNumber;
    private $newStatus = 'ABORTED_VE';
    private $reasonForAbort = "A more serious reason.";
    private $result;
    /**
     * @var \ApiErrorException $exception
     */
    private $exception;

    private $oldStatus;

    public function setMotTest($value)
    {
        $this->motTestNumber = $value;
    }

    public function setUserRole($value)
    {
        if ($value === "Vehicle Examiner") {
            $this->username = TestShared::USERNAME_ENFORCEMENT;
        } elseif ($value === "Tester") {
            $this->username = TestShared::USERNAME_TESTER1;
        }
    }

    public function setTestStatusBeforeTestAbort($value)
    {
        $this->oldStatus = $value;
    }

    public function setTestStatusAfterTestAbort()
    {

    }

    public function setReasonForAbort($value)
    {
        $this->reasonForAbort = $value;
    }

    public function success()
    {
        try {
            $updateMotTestHelper = new MotTestHelper(
                new \MotFitnesse\Util\CredentialsProvider($this->username, $this->password)
            );
            $this->result = $updateMotTestHelper->changeStatus(
                $this->motTestNumber,
                $this->newStatus,
                null,
                null,
                $this->reasonForAbort
            );
            $this->exception = null;
        } catch (ApiErrorException $ex) {
            $this->exception = $ex;
        }

        return $this->isSuccess();
    }

    private function isSuccess()
    {
        return !$this->exception;
    }

    public function errorMessages()
    {
        return $this->isSuccess() ? '' : $this->exception->getMessage();
    }
}
