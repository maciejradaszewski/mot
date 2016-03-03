<?php

namespace DvsaMotTest\Presenter;

use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Dto\MotTesting\MotTestOptionsDto;
use DvsaCommon\Enum\MotTestTypeCode;

class MotTestOptionsPresenter
{
    /** @var MotTestOptionsDto $motTestOptions */
    private $motTestOptions;
    protected $motTestNumber;

    public function __construct(MotTestOptionsDto $motTestOptions)
    {
        $this->motTestOptions = $motTestOptions;
    }

    /**
     * @return string
     */
    public function displayVehicleMakeAndModel()
    {
        if (empty($this->motTestOptions->getVehicleModel())) {
            return $this->motTestOptions->getVehicleMake();
        }

        return join(' ', [$this->motTestOptions->getVehicleMake(), $this->motTestOptions->getVehicleModel()]);
    }

    /**
     * @return string
     */
    public function displayVehicleRegistrationNumber()
    {
        return $this->motTestOptions->getVehicleRegistrationNumber();
    }

    /**
     * @return string
     */
    public function displayMotTestStartedDate()
    {
        return DateTimeDisplayFormat::textDateTime($this->motTestOptions->getMotTestStartedDate());
    }

    /**
     * @return bool
     */
    public function isMotTestRetest()
    {
        return ($this->motTestOptions->getMotTestTypeDto()->getCode() === MotTestTypeCode::RE_TEST);
    }

    /**
     * @return bool
     */
    public function isMotTest()
    {
        return !$this->isMotTestRetest();
    }

    /**
     * @return bool
     */
    public function getMotTestNumber()
    {
        return $this->motTestNumber;
    }

    /**
     * @param mixed $motTestNumber
     * @return MotTestOptionsPresenter
     */
    public function setMotTestNumber($motTestNumber)
    {
        $this->motTestNumber = $motTestNumber;
        return $this;
    }
}
