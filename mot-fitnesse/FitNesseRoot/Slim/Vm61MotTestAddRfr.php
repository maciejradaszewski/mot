<?php

require_once 'configure_autoload.php';
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Class Vm61MotTestAddRfr
 */
class Vm61MotTestAddRfr
{

    private $motTestNumber;
    private $rfrId;
    private $testItemSelectorId;
    private $type;
    private $result;
    private $currentUser;
    private $testStartedBy;
    private $password = TestShared::PASSWORD;
    private $requireClosing;
    private $newAborted;
    private $siteId;
    private $vehicleExaminerUsername;

    public function __construct($siteId, $vehicleExaminerUsername)
    {
        $this->siteId = $siteId;
        $this->vehicleExaminerUsername = $vehicleExaminerUsername;
    }

    public function setNewAborted($value)
    {
        if($value === 'NEW' || $value === 'ABORTED') {
            $this->newAborted = $value;
        } else {
            throw new \RuntimeException('Possible values are: NEW, ABORTED');
        }
    }

    public function setCurrentUser($value)
    {
        $this->currentUser = $value;
    }

    public function setTestStartedBy($value)
    {
        $this->testStartedBy = $value;
    }

    public function setRole()
    {
    }

    public function setRfrId($value)
    {
        $this->rfrId = $value;
    }

    public function setTestItemSelectorId($value)
    {
        $this->testItemSelectorId = $value;
    }

    public function setType($value)
    {
        $this->type = $value;
    }

    private function getMotTestHelper()
    {
        return new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider($this->testStartedBy, $this->password));
    }

    private function getMotTestHelperForVe()
    {
        return new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider(
            $this->vehicleExaminerUsername,
            TestShared::PASSWORD
            )
        );
    }

    private function resolveMotTestNumber()
    {
        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::create($this->testStartedBy, $this->password));
        $vehicleId = $vehicleTestHelper->generateVehicle(['testClass' => VehicleClassCode::CLASS_1]);

        $motTestHelper = $this->getMotTestHelper();
        $this->motTestNumber = $motTestHelper->createMotTest($vehicleId, null, $this->siteId)['motTestNumber'];

        if ($this->newAborted === 'NEW') {
            $this->requireClosing = true;
        } elseif($this->newAborted === 'ABORTED') {
            $this->getMotTestHelperForVe()->changeStatus($this->motTestNumber, 'ABORTED_VE', null, null, 'Because');
        }
    }


    public function success()
    {
        $this->requireClosing = false;
        $this->resolveMotTestNumber();
        $urlBuilder = (new UrlBuilder())->motTest()->routeParam('motTestNumber', $this->motTestNumber)->reasonsForRejection();

        $postArray = [
            'rfrId'              => $this->rfrId,
            'testItemSelectorId' => $this->testItemSelectorId,
            'type'               => $this->type
        ];

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            new \MotFitnesse\Util\CredentialsProvider($this->currentUser, $this->password),
            $urlBuilder,
            $postArray
        );
        $success =  TestShared::resultIsSuccess($this->result);

        if ($this->requireClosing) {
            $this->getMotTestHelper()->abortTest($this->motTestNumber);
        }

        return $success;
    }

    public function setInfoAboutMotTest()
    {
    }

    public function setInfoAboutRfr()
    {
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }
}
