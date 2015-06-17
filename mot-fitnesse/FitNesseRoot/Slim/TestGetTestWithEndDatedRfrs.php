<?php
use DvsaCommon\Enum\MotTestTypeCode;

/**
 *
 */
class TestGetTestWithEndDatedRfrs
{
    private $vehicleClass;

    private $rfrId;

    private $testData;

    private $motTestNumber;

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
        $this->createTestWithEndDatedRfrs();
        $this->getMotTest();
    }

    public function testsRfrList()
    {
        $rfrs = [];

        foreach ($this->testData['reasonsForRejection'] as $rfrGroups) {
            foreach ($rfrGroups as $rfr) {
                $rfrs[] = $rfr['rfrId'];
            }
        }
        return join(',', $rfrs);
    }

    private function createTestWithEndDatedRfrs()
    {
        $testSupportHelper = new TestSupportHelper();
        $rfrs = [['id' => $this->rfrId]];
        $motTest = $testSupportHelper->createIndependentMotTest(
            $this->vehicleClass,
            MotTestTypeCode::NORMAL_TEST,
            'AUTO',
            $rfrs
        );

        $this->motTestNumber = $motTest['motTestNumber'];

    }

    public function getMotTest()
    {
        $this->testData = (new MotTestHelper())->getMotTest($this->motTestNumber);
    }
}