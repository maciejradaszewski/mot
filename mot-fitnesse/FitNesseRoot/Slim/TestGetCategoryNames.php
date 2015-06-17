<?php

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

class TestGetCategoryNames
{
    private $vehicleClass;

    private $motTestNumber;

    public function setVehicleClass($value)
    {
        $this->vehicleClass = $value;
    }

    /**
     * @param mixed $motTestNumber
     * @return $this
     */
    public function setMotTestNumber($motTestNumber)
    {
        $this->motTestNumber = $motTestNumber;
    }

    public function success()
    {
        $urlBuilder = UrlBuilder::testItemCategoryName($this->motTestNumber);
        $url = $urlBuilder->toString();

        try {
            $result = TestShared::get($url, TestShared::USERNAME_TESTER1, TestShared::PASSWORD);
            return !empty($result);

        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
