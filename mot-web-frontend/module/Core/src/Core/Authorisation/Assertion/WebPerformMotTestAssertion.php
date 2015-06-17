<?php

namespace Core\Authorisation\Assertion;

use DvsaCommon\Auth\Assertion\PerformMotTestAssertion;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Common\MotTestTypeDto;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Utility\ArrayUtils;

class WebPerformMotTestAssertion
{
    private $assertion;

    public function __construct(PerformMotTestAssertion $assertion)
    {
        $this->assertion = $assertion;
    }

    public function assertGranted(MotTestDto $motTestData)
    {
        $testType = $this->extractTestType($motTestData);
        $ownerId = $this->extractOwnerId($motTestData);
        $vtsId = $this->extractVtsIdIfExists($motTestData);

        $this->assertion->assertGranted($testType, $ownerId, $vtsId);
    }

    public function isGranted($motTestData)
    {
        $testType = $this->extractTestType($motTestData);
        $ownerId = $this->extractOwnerId($motTestData);
        $vtsId = $this->extractVtsIdIfExists($motTestData);

        $this->assertion->isGranted($testType, $ownerId, $vtsId);
    }

    /**
     * @param MotTestDto $motTestData
     *
     * @return string
     */
    private function extractTestType(MotTestDto $motTestData)
    {
        $testType = $motTestData->getTestType();

        return $testType->getCode();
    }

    /**
     * @param MotTestDto $motTestData
     *
     * @return int
     */
    private function extractOwnerId(MotTestDto $motTestData)
    {
        $tester = $motTestData->getTester();

        return $tester->getId();
    }

    /**
     * @param MotTestDto $motTestData
     *
     * @return int
     */
    private function extractVtsIdIfExists(MotTestDto $motTestData)
    {
        $vtsData = $motTestData->getVehicleTestingStation();

        return ArrayUtils::tryGet($vtsData, 'id', null);
    }
}
