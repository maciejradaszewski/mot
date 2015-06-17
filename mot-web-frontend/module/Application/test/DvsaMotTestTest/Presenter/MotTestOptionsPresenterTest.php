<?php

namespace DvsaMotTest\Presenter;

use DvsaCommon\Dto\MotTesting\MotTestOptionsDto;

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
}
