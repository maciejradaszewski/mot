<?php

namespace DvsaCommonTest\Dto\Vehicle\History;

use DvsaCommon\Dto\Vehicle\History\VehicleHistoryItemDto;
use DvsaCommon\Enum\MotTestStatusName;

class VehicleHistoryItemDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDisplayIssuedDate_forCorrectDate_returnsCorrectString()
    {
        $vehicleHistoryItem = new VehicleHistoryItemDto();
        $vehicleHistoryItem->setIssuedDate('2014-05-12');

        $this->assertEquals('12 May 2014', $vehicleHistoryItem->getDisplayIssuedDate());
    }

    public function testGetDisplayIssuedDate_whenNotSet_returnsNA()
    {
        $vehicleHistoryItem = new VehicleHistoryItemDto();

        $this->assertEquals('n/a', $vehicleHistoryItem->getDisplayIssuedDate());
    }

    public function testHasPassed_whenTestHasPassed_returnsTrue()
    {
        $vehicleHistoryItem = new VehicleHistoryItemDto();
        $vehicleHistoryItem->setStatus(MotTestStatusName::PASSED);

        $this->assertTrue($vehicleHistoryItem->hasPassed());
    }

    public function testHasPassed_whenTestIsNotPassed_returnsFalse()
    {
        $vehicleHistoryItem = new VehicleHistoryItemDto();
        $vehicleHistoryItem->setStatus(MotTestStatusName::ABANDONED);

        $this->assertFalse($vehicleHistoryItem->hasPassed());
    }

    public function testGetDisplayStatusAndHasPassed_forPassedTest_returnsPass()
    {
        $this->checkDisplayStatus(
            MotTestStatusName::PASSED,
            VehicleHistoryItemDto::DISPLAY_PASS_STATUS_VALUE
        );
    }

    public function testGetDisplayStatusAndHasPassed_forFailedTest_returnsFail()
    {
        $this->checkDisplayStatus(
            MotTestStatusName::FAILED,
            VehicleHistoryItemDto::DISPLAY_FAIL_STATUS_VALUE
        );
    }

    public function testGetDisplayStatusAndHasPassed_forAbandonedTest_returnsAban()
    {
        $this->checkDisplayStatus(
            MotTestStatusName::ABANDONED,
            VehicleHistoryItemDto::DISPLAY_ABAN_STATUS_VALUE
        );
    }

    private function checkDisplayStatus($inputStatus, $expectedStatusString)
    {
        $vehicleHistoryItem = new VehicleHistoryItemDto();
        $vehicleHistoryItem->setStatus($inputStatus);

        $this->assertEquals($expectedStatusString, $vehicleHistoryItem->getDisplayStatus());
    }
}
