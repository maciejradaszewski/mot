<?php

use DvsaCommon\Enum\MotTestTypeCode;
use MotFitnesse\Util\CredentialsProvider;

class VM7788MoreRecentTestsPreventsReplacement {

    private $motTestNumber;
    private $vehicleExaminerUsername;
    private $vehicleId;
    private $siteId;
    private $testerUsername;

    /**
     * @param string $testerUsername
     */
    public function setTesterUsername($testerUsername)
    {
        $this->testerUsername = $testerUsername;
    }

    /**
     * @param int $siteId
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * @param int $vehicleId
     */
    public function setVehicleId($vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }

    /**
     * @param string $motTestNumber
     */
    public function setMotTestNumber($motTestNumber)
    {
        $this->motTestNumber = $motTestNumber;
    }

    /**
     * @param string $username
     */
    public function setVehicleExaminerUsername($username)
    {
        $this->vehicleExaminerUsername = $username;
    }

    public function execute()
    {
        $motTestHelper = new MotTestHelper(new CredentialsProvider(
            $this->vehicleExaminerUsername,
            \MotFitnesse\Util\TestShared::PASSWORD
        ));

        $motTestCreate = (new \MotFitnesse\Testing\Objects\MotTestCreate())
            ->vehicleId($this->vehicleId)
            ->siteId($this->siteId)
            ->originalMotTestNumber($this->motTestNumber)
            ->motTestType(MotTestTypeCode::STATUTORY_APPEAL);

        $motTestHelper->createPassedTest($motTestCreate);
    }
    /**
     * @return string allowEdit
     */
    public function ableToReplaceOriginal()
    {
        $motTestHelper = new MotTestHelper(new CredentialsProvider(
            $this->testerUsername,
            \MotFitnesse\Util\TestShared::PASSWORD
        ));

        $result = $motTestHelper->getMotTestHistory($this->vehicleId);

        return end($result)['allowEdit'];
    }
}

