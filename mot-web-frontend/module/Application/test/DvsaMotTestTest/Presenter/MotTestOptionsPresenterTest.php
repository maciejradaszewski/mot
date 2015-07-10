<?php

namespace DvsaMotTest\Presenter;

use DvsaCommon\Dto\Common\MotTestTypeDto;
use DvsaCommon\Dto\MotTesting\MotTestOptionsDto;
use DvsaCommon\Enum\MotTestTypeCode;

class MotTestOptionsPresenterTest extends \PHPUnit_Framework_TestCase
{
    public function testDisplayVehicleMakeAndModel()
    {
        $presenter = new MotTestOptionsPresenter(
            (new MotTestOptionsDto)
                ->setVehicleMake('make')
                ->setVehicleModel('model')
        );

        $this->assertEquals('make model', $presenter->displayVehicleMakeAndModel());
    }

    public function testDisplayVehicleRegistrationNumber()
    {
        $presenter = new MotTestOptionsPresenter(
            (new MotTestOptionsDto)
                ->setVehicleRegistrationNumber('registration number')
        );

        $this->assertEquals('registration number', $presenter->displayVehicleRegistrationNumber());
    }

    public function testDisplayMotTestStartedDate()
    {
        $presenter = new MotTestOptionsPresenter(
            (new MotTestOptionsDto)
                ->setMotTestStartedDate('2014-12-09T16:50:59Z')
        );

        $this->assertEquals('9 December 2014, 4:50pm', $presenter->displayMotTestStartedDate());
    }

    public function testIfMotTestTypeCodeReTestReturnTrueForIsMotTestReTest()
    {
        $presenter = new MotTestOptionsPresenter(
            (new MotTestOptionsDto())->setMotTestTypeDto(
                (new MotTestTypeDto())->setCode(MotTestTypeCode::RE_TEST)
            )
        );

        $this->assertTrue($presenter->isMotTestRetest());
        $this->assertFalse($presenter->isMotTest());
    }

    public function testIfMotTestTypeCodeReTestReturnFalseForIsMotTestReTest()
    {
        $presenter = new MotTestOptionsPresenter(
            (new MotTestOptionsDto())->setMotTestTypeDto(
                (new MotTestTypeDto())->setCode(MotTestTypeCode::NORMAL_TEST)
            )
        );

        $this->assertTrue($presenter->isMotTest());
        $this->assertFalse($presenter->isMotTestRetest());
    }
}
