<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\BrakeTest\BrakeTestTypeDto;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Equipment\EquipmentDto;
use DvsaCommon\Dto\Security\RolesMapDto;

/**
 * Class VehicleTestingStationDto
 *
 * @package DvsaCommon\Dto\Site
 */
class VehicleTestingStationDto extends SiteDto
{
    /** @var  string[] */
    private $testClasses;

    /** @var  BrakeTestTypeDto */
    private $defaultBrakeTestClass1And2;
    /** @var  BrakeTestTypeDto */
    private $defaultServiceBrakeTestClass3AndAbove;
    /** @var  BrakeTestTypeDto */
    private $defaultParkingBrakeTestClass3AndAbove;

    /** @var  FacilityDto[] */
    private $facilities;
    /** @var  EquipmentDto[] */
    private $equipments;
    /** @var  SiteTestingDailyScheduleDto[] */
    private $siteTestingSchedule;

    /** @var  MotTestDto[] */
    private $motTests;

    /** @var RolesMapDto */
    private $positions;

    /** @var  string */
    private $status;

    /**
     * @return string[]
     */
    public function getTestClasses()
    {
        return $this->testClasses;
    }

    /**
     * @param \string[] $testClasses
     *
     * @return $this
     */
    public function setTestClasses($testClasses)
    {
        $this->testClasses = $testClasses;
        return $this;
    }

    /**
     * @return BrakeTestTypeDto
     */
    public function getDefaultBrakeTestClass1And2()
    {
        return $this->defaultBrakeTestClass1And2;
    }

    /**
     * @param BrakeTestTypeDto $defaultBrakeTestClass1And2
     *
     * @return $this
     */
    public function setDefaultBrakeTestClass1And2($defaultBrakeTestClass1And2)
    {
        $this->defaultBrakeTestClass1And2 = $defaultBrakeTestClass1And2;
        return $this;
    }

    /**
     * @return BrakeTestTypeDto
     */
    public function getDefaultServiceBrakeTestClass3AndAbove()
    {
        return $this->defaultServiceBrakeTestClass3AndAbove;
    }

    /**
     * @param BrakeTestTypeDto $defaultServiceBrakeTestClass3AndAbove
     *
     * @return $this
     */
    public function setDefaultServiceBrakeTestClass3AndAbove($defaultServiceBrakeTestClass3AndAbove)
    {
        $this->defaultServiceBrakeTestClass3AndAbove = $defaultServiceBrakeTestClass3AndAbove;
        return $this;
    }

    /**
     * @return BrakeTestTypeDto
     */
    public function getDefaultParkingBrakeTestClass3AndAbove()
    {
        return $this->defaultParkingBrakeTestClass3AndAbove;
    }

    /**
     * @param BrakeTestTypeDto $defaultParkingBrakeTestClass3AndAbove
     *
     * @return $this
     */
    public function setDefaultParkingBrakeTestClass3AndAbove($defaultParkingBrakeTestClass3AndAbove)
    {
        $this->defaultParkingBrakeTestClass3AndAbove = $defaultParkingBrakeTestClass3AndAbove;
        return $this;
    }

    /**
     * @return FacilityDto[]
     */
    public function getFacilities()
    {
        return $this->facilities;
    }

    /**
     * @param FacilityDto[] $facilities
     *
     * @return $this
     */
    public function setFacilities($facilities)
    {
        $this->facilities = $facilities;
        return $this;
    }

    /**
     * @return \DvsaCommon\Dto\Equipment\EquipmentDto[]
     */
    public function getEquipments()
    {
        return $this->equipments;
    }

    /**
     * @param \DvsaCommon\Dto\Equipment\EquipmentDto[] $equipments
     *
     * @return $this
     */
    public function setEquipments($equipments)
    {
        $this->equipments = $equipments;
        return $this;
    }

    /**
     * @return SiteTestingDailyScheduleDto[]
     */
    public function getSiteTestingSchedule()
    {
        return $this->siteTestingSchedule;
    }

    /**
     * @param SiteTestingDailyScheduleDto[] $siteTestingSchedule
     *
     * @return $this
     */
    public function setSiteTestingSchedule($siteTestingSchedule)
    {
        $this->siteTestingSchedule = $siteTestingSchedule;
        return $this;
    }

    /**
     * @return \DvsaCommon\Dto\Common\MotTestDto[]
     */
    public function getMotTests()
    {
        return $this->motTests;
    }

    /**
     * @param \DvsaCommon\Dto\Common\MotTestDto[] $motTests
     *
     * @return $this
     */
    public function setMotTests($motTests)
    {
        $this->motTests = $motTests;
        return $this;
    }

    /**
     * @return RolesMapDto
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param RolesMapDto $positions
     *
     * @return $this
     */
    public function setPositions($positions)
    {
        $this->positions = $positions;
        return $this;
    }

    /**
     * #TODO Used but never set
     */
    public function getStatus()
    {
        return $this->status;
    }
}
