<?php

namespace DvsaMotApi\Service\Validator\Odometer;

use Api\Check\CheckMessage as CM;
use Api\Check\CheckResult;
use DvsaCommon\Constants\OdometerReadingResultType;
use DvsaCommon\Constants\OdometerUnit;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommon\Dto\Common\OdometerReadingDTO;

/**
 * Encapsulates all validation rules for OdometerReading
 *
 * Class OdometerReadingValidator
 *
 * @package DvsaMotApi\Service\Odometer\Validator
 */
class OdometerReadingValidator
{

    /**
     * @param OdometerReadingDTO $reading
     * @param null               $fieldContext
     *
     * @return CheckResult
     */
    public function validate(OdometerReadingDTO $reading, $fieldContext = null)
    {
        $result = CheckResult::ok();
        $errorCodeInvalidData = BadRequestException::ERROR_CODE_INVALID_DATA;

        if (!OdometerReadingResultType::isValid($reading->getResultType())) {
            $result->add(
                CM::create($fieldContext)->field("resultType")->code(BadRequestException::ERROR_CODE_INVALID_DATA)
                    ->text("Invalid odometer result type")
            );
        }
        if ($reading->getResultType() === OdometerReadingResultType::OK) {
            $value = $reading->getValue();
            if (!is_null($value) && (!is_int($value) || $value < 0) || is_null($value)) {
                $result->add(
                    CM::create($fieldContext)->field("value")->code($errorCodeInvalidData)->text(
                        "Invalid odometer value"
                    )
                );
            }
            if (!OdometerUnit::isValid($reading->getUnit())) {
                $result->add(
                    CM::create($fieldContext)->field("unit")->code(BadRequestException::ERROR_CODE_INVALID_DATA)
                        ->text('Invalid odometer unit')
                );
            }
        } else {
            if (!is_null($reading->getValue())) {
                $result->add(
                    CM::create($fieldContext)->field("value")->code(
                        BadRequestException::ERROR_CODE_INVALID_ENTITY_STATE
                    )
                        ->text("Odometer value given though result type is not OK")
                );
            }
            if (!is_null($reading->getUnit())) {
                $result->add(
                    CM::create($fieldContext)->field("unit")->code(BadRequestException::ERROR_CODE_INVALID_ENTITY_STATE)
                        ->text("Odometer unit given though result type is not OK")
                );
            }
        }
        return $result;
    }
}
