<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;


/**
 * Class Vm1128RemoveReasonForRejection
 */
class Vm1128RemoveReasonForRejection
{

    private $rfrIdToBeRemoved;
    private $currentUser;
    private $testStartedBy;
    private $password = TestShared::PASSWORD;
    private $errorMessage = "";
    private $motTestNumber;
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

    public function setRfrIdToBeRemoved($rfrIdToBeRemoved)
    {
        $this->rfrIdToBeRemoved = $rfrIdToBeRemoved;
    }

    private function getMotTestHelperForVe()
    {
        return new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider(
            $this->vehicleExaminerUsername,
            TestShared::PASSWORD
            )
        );
    }

    private function resolveMotTestNumber(MotTestHelper $motTestHelper)
    {
        $vehicleTestHelper = new VehicleTestHelper(FitMotApiClient::create($this->testStartedBy, $this->password));
        $vehicleId = $vehicleTestHelper->generateVehicle();

        $this->motTestNumber = $motTestHelper->createMotTest($vehicleId, null, $this->siteId)['motTestNumber'];

        if ($this->newAborted === 'NEW') {
            $this->requireClosing = true;
        } elseif ($this->newAborted === 'ABORTED') {
            $this->getMotTestHelperForVe()->changeStatus($this->motTestNumber, 'ABORTED_VE', null, null, 'Because');
        }
    }

    public function success()
    {
        $result = false;
        $this->requireClosing = false;
        $this->errorMessage = "";
        $creatorMotTestHelper
            = new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider($this->testStartedBy, $this->password));

        $this->resolveMotTestNumber($creatorMotTestHelper);
        try {
            $creatorMotTestHelper->addRfr($this->motTestNumber, $this->rfrIdToBeRemoved);
            $motTestData = $creatorMotTestHelper->getMotTest($this->motTestNumber);
            $motTestRfrIdToDelete = $motTestData['reasonsForRejection']['FAIL'][0]['id'];

            $currentUserApiClient = FitMotApiClient::create($this->currentUser, $this->password);
            $currentUserApiClient->delete(
                (new UrlBuilder())->motTest()->routeParam('motTestNumber', $this->motTestNumber)->reasonsForRejection()->routeParam(
                    'motTestRfrId',
                    $motTestRfrIdToDelete
                )
            );

            $result = true;
        } catch (ApiErrorException $e) {
            $this->errorMessage = $e->getDisplayMessage();
        }

        if ($this->requireClosing) {
            $creatorMotTestHelper->abortTest($this->motTestNumber);
        }
        return $result;
    }


    public function errorMessages()
    {
        return $this->errorMessage;
    }
}
