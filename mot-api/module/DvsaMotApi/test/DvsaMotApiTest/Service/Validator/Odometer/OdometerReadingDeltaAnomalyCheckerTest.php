<?php

namespace DvsaMotApiTest\Service\Validator\Odometer;

use DvsaCommon\Constants\OdometerReadingResultType;
use DvsaCommon\Constants\OdometerUnit;
use DvsaEntities\Entity\OdometerReading;
use DvsaEntities\Repository\ConfigurationRepository;
use DvsaMotApi\Service\Validator\Odometer\OdometerReadingDeltaAnomalyChecker;

/**
 * Class OdometerReadingDeltaAnomalyCheckerTest
 */
class OdometerReadingDeltaAnomalyCheckerTest extends \PHPUnit_Framework_TestCase
{
    private $configurationRepository;

    /**
     * @var OdometerReadingDeltaAnomalyChecker $checker
     */
    private $checker;

    public function setUp()
    {
        $this->configurationRepository = \DvsaCommonTest\TestUtils\XMock::of(ConfigurationRepository::class);
        $this->checker = new OdometerReadingDeltaAnomalyChecker(
            $this->configurationRepository
        );
    }

    public function testCheck_givenReadingIsOK_and_prevAndCurrReadingsTheSame_shouldProduceAppropriateMessage()
    {
        $value = 123;
        $reading = OdometerReading::create()
            ->setValue($value)->setUnit(OdometerUnit::KILOMETERS)
            ->setResultType(OdometerReadingResultType::OK);

        $prevReading = OdometerReading::create()
            ->setValue($value)->setUnit(OdometerUnit::KILOMETERS)
            ->setResultType(OdometerReadingResultType::OK);

        $result = $this->checker->check($reading, $prevReading);
        $this->assertTrue(
            $result->messageWithTextExists(OdometerReadingDeltaAnomalyChecker::CURRENT_EQ_PREVIOUS),
            "The message about previous and current readings equal is expected!"
        );
    }

    public function testCheck_givenReadingIsOK_and_currReadingLowerThanPrevious_shouldProduceAppropriateMessage()
    {
        $prevValue = 123;
        $currValue = $prevValue - 1;
        $reading = OdometerReading::create()
            ->setValue($currValue)->setUnit(OdometerUnit::KILOMETERS)
            ->setResultType(OdometerReadingResultType::OK);

        $prevReading = OdometerReading::create()
            ->setValue($prevValue)->setUnit(OdometerUnit::KILOMETERS)
            ->setResultType(OdometerReadingResultType::OK);

        $result = $this->checker->check($reading, $prevReading);

        $this->assertTrue(
            $result->messageWithTextExists(OdometerReadingDeltaAnomalyChecker::CURRENT_LOWER_THAN_PREVIOUS),
            "The message about current reading lower than previous is expected!"
        );
    }

    public function testCheck_givenReadingIsOK_and_currMuchHigherThanPrevious_shouldProduceAppropriateMessage()
    {
        $muchHigherLimit = 25000;
        $prevValue = 123;
        $currValue = $prevValue + $muchHigherLimit;
        $reading = OdometerReading::create()
            ->setValue($currValue)->setUnit(OdometerUnit::KILOMETERS)
            ->setResultType(OdometerReadingResultType::OK);

        $prevReading = OdometerReading::create()
            ->setValue($prevValue)->setUnit(OdometerUnit::KILOMETERS)
            ->setResultType(OdometerReadingResultType::OK);

        $this->configurationRepository->expects($this->any())->method("getParam")
            ->will($this->returnValue($muchHigherLimit));

        $result = $this->checker->check($reading, $prevReading);

        $this->assertTrue(
            $result->messageWithTextExists(OdometerReadingDeltaAnomalyChecker::VALUE_SIGNIFICANTLY_HIGHER),
            "The message about current reading being significantly higher than previous is expected!"
        );
    }

    public function testCheck_givenVehicleHasNoOdometer_shouldExpectNoMessage()
    {
        $prevValue = 123;
        $reading = OdometerReading::create()->setResultType(OdometerReadingResultType::NO_ODOMETER);
        $prevReading = OdometerReading::create()
            ->setValue($prevValue)->setUnit(OdometerUnit::KILOMETERS)
            ->setResultType(OdometerReadingResultType::OK);

        $result = $this->checker->check($reading, $prevReading);

        $this->assertTrue($result->isEmpty(), "No message expected when there is no odometer");
    }

    public function testCheck_givenOdometerIsNotReadable_shouldExpectNoMessage()
    {
        $prevValue = 123;
        $reading = OdometerReading::create()->setResultType(OdometerReadingResultType::NOT_READABLE);
        $prevReading = OdometerReading::create()
            ->setValue($prevValue)->setUnit(OdometerUnit::KILOMETERS)
            ->setResultType(OdometerReadingResultType::OK);

        $result = $this->checker->check($reading, $prevReading);

        $this->assertTrue($result->isEmpty(), "No message expected when odometer could not be read");
    }

    public function testCheck_givenReadingIsOK_prevCurrReadingsDeltaIsNotProvided_shouldExpectNoMessage()
    {
        $reading = OdometerReading::create()->setResultType(OdometerReadingResultType::OK);
        $prevReading = OdometerReading::create()
            ->setValue(123)->setUnit(OdometerUnit::KILOMETERS)
            ->setResultType(OdometerReadingResultType::OK);

        $result = $this->checker->check($reading, $prevReading);

        $this->assertTrue($result->isEmpty(), "No message expected when delta not captured");
    }
}
