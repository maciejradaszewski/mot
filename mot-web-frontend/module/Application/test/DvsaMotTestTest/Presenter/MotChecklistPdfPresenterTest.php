<?php


namespace Application\test\DvsaMotTestTest\Presenter;


use Dvsa\Mot\Frontend\AuthenticationModule\Model\MotFrontendIdentityInterface;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\Dto\VehicleClassification\VehicleClassDto;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotTest\Presenter\MotChecklistPdfPresenter;

class MotChecklistPdfPresenterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataProviderTestFieldsAreDifferentForClass1And2
     * @param int $classCode
     * @param int $fieldCount
     * @throws \Exception
     */
    public function testFieldsAreDifferentForClass1And2($classCode, $fieldCount){
        $presenter = new MotChecklistPdfPresenter();
        $testDto = new MotTestDto();
        $vehicleDto = new VehicleDto();
        $testClass = new VehicleClassDto();
        $testClass->setCode($classCode);

        $testDto->setVehicle($vehicleDto);
        $testDto->setVehicleClass($testClass);

        $presenter->setIdentity(XMock::of(MotFrontendIdentityInterface::class));
        $presenter->setMotTest($testDto);
        $this->assertCount($fieldCount,$presenter->getDataFields());
    }

    public function dataProviderTestFieldsAreDifferentForClass1And2()
    {
        return [
            [VehicleClassCode::CLASS_1, 10],
            [VehicleClassCode::CLASS_2, 10],
            [VehicleClassCode::CLASS_3, 11],
            [VehicleClassCode::CLASS_4, 11],
            [VehicleClassCode::CLASS_5, 11],
            [VehicleClassCode::CLASS_7, 11],
        ];
    }
}