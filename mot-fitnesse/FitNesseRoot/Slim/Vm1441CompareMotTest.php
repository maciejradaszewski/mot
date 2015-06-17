<?php

require_once 'configure_autoload.php';
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class Vm1441CompareMotTest
 */
class Vm1441CompareMotTest
{
    const MOT_TEST_NUMBER = 'motTestNumber';
    const MOT_TEST_NUMBER_TO_COMPARE = 'motTestNumberToCompare';

    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;
    public $apiResult = null;

    private $motTestNumber;
    private $motTestNumberToCompare;

    /**
     * @param mixed $motTestNumber
     *
     * @return Vm1441CompareMotTest
     */
    public function setMotTestNumber($motTestNumber)
    {
        $this->motTestNumber = $motTestNumber;
        return $this;
    }

    /**
     * @param mixed $motTestNumberToCompare
     *
     * @return Vm1441CompareMotTest
     */
    public function setMotTestNumberToCompare($motTestNumberToCompare)
    {
        $this->motTestNumberToCompare = $motTestNumberToCompare;
        return $this;
    }

    public function success()
    {
        $this->apiResult = TestShared::execCurlForJsonFromUrlBuilder(
            $this,
            (new UrlBuilder())->compareMotTest()
                ->queryParam(self::MOT_TEST_NUMBER, $this->motTestNumber)
                ->queryParam(self::MOT_TEST_NUMBER_TO_COMPARE, $this->motTestNumberToCompare)
        );
        return TestShared::resultIsSuccess($this->apiResult);
    }

    public function errorMessage()
    {
        return TestShared::errorMessages($this->apiResult);
    }
}
