<?php
use DvsaCommon\Enum\MotTestStatusName;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\TestShared;

/**
 *
 */
class TestAddingEndDatedRfrs
{
    private $vehicleClass;

    private $rfrId;

    private $motTestNumber;

    private $tester;

    private $addingError;

    public function reset()
    {
        $this->addingError = null;
    }

    /**
     * @param mixed $vehicleClass
     */
    public function setVehicleClass($vehicleClass)
    {
        $this->vehicleClass = $vehicleClass;
    }

    /**
     * @param mixed $rfrId
     */
    public function setRfrId($rfrId)
    {
        $this->rfrId = $rfrId;
    }

    public function execute()
    {
        $this->createActiveTest();
        try {
            $t = new MotTestHelper(new CredentialsProvider($this->tester['username'], TestShared::PASSWORD));
            $t->addRfr($this->motTestNumber, $this->rfrId);
        } catch (Exception $ex) {
            $this->addingError = $ex->getMessage();
        }
    }

    public function allowed()
    {
        return is_null($this->addingError) ? 'YES' : 'NO';
    }

    public function errorMessage()
    {
        return $this->addingError;
    }

    private function createActiveTest()
    {
        $testSupportHelper = new TestSupportHelper();
        $schememgt = $testSupportHelper->createSchemeManager()['username'];
        $ae = $testSupportHelper->createAuthorisedExaminer(
            $testSupportHelper->createAreaOffice1User()['username']
            , null, 1000);
        $site = $testSupportHelper->createVehicleTestingStation(
            $testSupportHelper->createAreaOffice1User()['username'],
            $ae['id'],
            'vts'
        );
        $this->tester = $testSupportHelper->createTester($schememgt, [$site['id']]);
        $vehicleId = (new VehicleTestHelper(FitMotApiClient::create($this->tester['username'], TestShared::PASSWORD)))
            ->generateVehicle(['testClass' => $this->vehicleClass]);
        $motTest = $testSupportHelper->createMotTest(
            $this->tester['username'], $site['id'], $vehicleId, MotTestStatusName::ACTIVE
        );
        $this->motTestNumber = $motTest['motTestNumber'];
    }
}