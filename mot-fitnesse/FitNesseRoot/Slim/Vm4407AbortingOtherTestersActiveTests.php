<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\ReasonForCancelId;

/**
 * Checks if testers, site admins and site managers of a specific VTS are able to abort MOT test running on their
 * corresponding VTSs
 */
class Vm4407AbortingOtherTestersActiveTests
{
    /**
     * @var string
     */
    private $testerAbortingMotTestUsername;

    /**
     * @var string (numeric) MOT Test Number 12-digit
     */
    private $motTestNumber;

    /**
     * @param $v string username (login)
     */
    public function setPersonAttemptingToAbort($v)
    {
        $this->testerAbortingMotTestUsername = $v;
    }

    /**
     * @param $v string numeric (12-digits, never starts with 0)
     */
    public function setMotTestNumber($v)
    {
        $this->motTestNumber = $v;
    }

    /**
     * Fitnesse test logic. Tries to abort MOT test
     *
     * @return string
     */
    public function motTestStatusWillBe()
    {
        $result = null;
        try {
            $result = (new TestSupportHelper())->abortMotTest(
                $this->testerAbortingMotTestUsername,
                $this->motTestNumber,
                ReasonForCancelId::ABORT
            );
        } catch (ApiErrorException $ex) {
            if ($ex->isForbiddenException()) {
                return 'Forbidden';
            }
            return $ex->getDisplayMessage();
        }

        return $result['status'] . ' Successfully';
    }

    /**
     * Not used by fitnesse code. Passed only to make it more readable in the fitnesse table
     *
     * @param string $v
     */
    public function setInfo($v)
    {
    }

    /**
     * Not used by fitnesse code. Passed only to make it more readable in the fitnesse table
     *
     * @param string $v
     */
    public function setTesterStartingTest($v)
    {
    }
}
