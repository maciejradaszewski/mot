<?php
namespace Dvsa\Mot\Behat\Support\Data;

use Dvsa\Mot\Behat\Support\Api\BrakeTestResult;
use Dvsa\Mot\Behat\Support\Api\DemoTest;
use Dvsa\Mot\Behat\Support\Api\MotTest;
use Dvsa\Mot\Behat\Support\Api\OdometerReading;
use Dvsa\Mot\Behat\Support\Api\ReasonForRejection;
use Dvsa\Mot\Behat\Support\Api\Session\AuthenticatedUser;
use Dvsa\Mot\Behat\Support\Data\Params\MotTestParams;
use Dvsa\Mot\Behat\Support\Helper\TestSupportHelper;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\Enum\MotTestTypeCode;

class DemoMotTestData extends AbstractMotTestData
{
    private $demoTest;

    public function __construct(
        DemoTest $demoTest,
        UserData $userData,
        MotTest $motTest,
        BrakeTestResult $brakeTestResult,
        OdometerReading $odometerReading,
        ReasonForRejection $reasonForRejection,
        TestSupportHelper $testSupportHelper
    )
    {
        parent::__construct($userData, $motTest, $brakeTestResult, $odometerReading, $reasonForRejection, $testSupportHelper);

        $this->demoTest = $demoTest;
    }

    public function create(AuthenticatedUser $tester, VehicleDto $vehicle)
    {
        $mot = $this
            ->demoTest
            ->startMotTest(
                $tester->getAccessToken(),
                $vehicle->getId(),
                $vehicle->getVehicleClass()->getCode()
            );

        $dto = $this->mapToMotTestDto(
            $tester,
            $vehicle,
            $mot->getBody()->getData()[MotTestParams::MOT_TEST_NUMBER],
            MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING
        );

        $this->motCollection->add($dto, $dto->getMotTestNumber());

        return $dto;
    }

    public function createPassedMotTest(AuthenticatedUser $tester, VehicleDto $vehicle)
    {
        $mot = $this->create($tester, $vehicle);
        return $this->passMotTestWithDefaultBrakeTestAndMeterReading($mot);
    }

    public function createFailedMotTest(AuthenticatedUser $tester, VehicleDto $vehicle)
    {
        $mot = $this->create($tester, $vehicle);
        return $this->failMotTestWithDefaultBrakeTestAndMeterReading($mot);
    }
}
