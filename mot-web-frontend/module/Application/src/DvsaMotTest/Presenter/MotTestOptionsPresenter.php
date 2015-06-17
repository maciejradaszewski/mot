<?php

namespace DvsaMotTest\Presenter;

use DvsaCommon\Date\DateTimeDisplayFormat;
use DvsaCommon\Dto\MotTesting\MotTestOptionsDto;

class MotTestOptionsPresenter
{
    /** @var MotTestOptionsDto $motTestOptions */
    private $motTestOptions;

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
}
