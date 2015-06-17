<?php

namespace DvsaMotApiTest\Service\Validator\Odometer;

use DvsaCommon\Constants\OdometerReadingResultType;
use Api\Check\CheckResult;
use DvsaCommon\Dto\Common\OdometerReadingDTO;
use DvsaMotApi\Service\Validator\Odometer\OdometerReadingValidator;

/**
 * Class OdometerReadingValidatorTest
 */
class OdometerReadingValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OdometerReadingValidator $odometerReadingValidator
     */
    private $odometerReadingValidator;

    public static function dataProviderGivenInvalidReadingAsInput()
    {
        $invalidResultType = $invalidUnit = "><>";
        return [
            [
                'reading' => OdometerReadingDTO::create()->setResultType($invalidResultType),
                'fields'  => ['resultType']
            ],
            [
                'reading' => OdometerReadingDTO::create()->setResultType(OdometerReadingResultType::OK)
                        ->setValue(null),
                'fields'  => ['value']
            ],
            [
                'reading' => OdometerReadingDTO::create()->setResultType(OdometerReadingResultType::OK)
                        ->setValue(-1),
                'fields'  => ['value']
            ],
            [
                'reading' => OdometerReadingDTO::create()->setResultType(OdometerReadingResultType::OK)
                        ->setValue(1)->setUnit($invalidUnit),
                'fields'  => ['unit']
            ],
            [
                'reading' => OdometerReadingDTO::create()->setResultType(OdometerReadingResultType::NO_ODOMETER)
                        ->setUnit($invalidUnit),
                'fields'  => ['unit']
            ],
            [
                'reading' => OdometerReadingDTO::create()->setResultType(OdometerReadingResultType::NO_ODOMETER)
                        ->setUnit($invalidUnit)->setValue("nonEmptyValue"),
                'fields'  => ['value']
            ],
        ];
    }

    public function setUp()
    {
        $this->odometerReadingValidator = new OdometerReadingValidator();
    }

    /**
     * @dataProvider dataProviderGivenInvalidReadingAsInput
     *
     * @param $reading
     * @param $fields
     */
    public function testValidateGivenInvalidReadingAsInputShouldReturnErrorForFields($reading, $fields)
    {
        $result = $this->odometerReadingValidator->validate($reading);

        $this->assertCheckMessageForFieldsReturned($result, $fields);
    }

    /**
     * Checks if for every field in the array there is at least one message in the check result
     *
     * @param CheckResult $checkResult
     * @param array       $fields
     */
    private function assertCheckMessageForFieldsReturned(CheckResult $checkResult, array $fields)
    {
        foreach ($fields as $field) {
            $this->assertNotEmpty(
                $checkResult->getMessagesForField($field),
                "The result does not contain message for field $field"
            );
        }
    }
}
