<?php

namespace DvsaMotApi\Controller\Validator;

use Api\Check\CheckMessage;
use Api\Check\CheckResult;
use Api\Check\CheckResultExceptionTranslator;
use DvsaMotApi\Controller\DemoTestController;

/**
 * Class CreateMotTestRequestValidator
 */
class CreateMotTestRequestValidator
{
    const FIELD_VEHICLE_ID = 'vehicleId';
    const FIELD_DVLA_VEHICLE_ID = 'dvlaVehicleId';
    const FIELD_VTS_ID = 'vehicleTestingStationId';
    const FIELD_HAS_REGISTRATION = 'hasRegistration';
    const FIELD_COLOURS_PRIMARY = 'primaryColour';
    const FIELD_COLOURS_SECONDARY = 'secondaryColour';
    const FIELD_VEHICLE_CLASS_CODE = 'vehicleClassCode';

    public static function validate(&$data)
    {
        $checkResult = CheckResult::ok();

        $hasNotKey = function ($key) use (&$data) {
            return !array_key_exists($key, $data);
        };
        $addErrorFor = function ($field) use (&$checkResult) {
            $checkResult->add(CheckMessage::withError("$field is required"));
        };
        $neitherVehicleSourcesSet = $hasNotKey(self::FIELD_VEHICLE_ID) && $hasNotKey(self::FIELD_DVLA_VEHICLE_ID);
        $bothVehicleSourcesSet = !$hasNotKey(self::FIELD_VEHICLE_ID) && !$hasNotKey(self::FIELD_DVLA_VEHICLE_ID);

        if ($neitherVehicleSourcesSet || $bothVehicleSourcesSet) {
            $addErrorFor('Either vehicleId or dvsaVehicleId');
        }
        $requiredKeys = [self::FIELD_COLOURS_PRIMARY, self::FIELD_HAS_REGISTRATION,
                         self::FIELD_HAS_REGISTRATION, self::FIELD_VTS_ID,
                         self::FIELD_VEHICLE_CLASS_CODE
        ];
        foreach ($requiredKeys as $key) {
            if ($hasNotKey($key)) {
                $addErrorFor($key);
            }
        }

        CheckResultExceptionTranslator::tryThrowBadRequestException($checkResult);
    }

    public static function validateDemo(&$data)
    {
        $checkResult = CheckResult::ok();

        $requiredKeys = [
            DemoTestController::FIELD_VEHICLE_ID,
            DemoTestController::FIELD_PRIMARY_COLOUR,
            DemoTestController::FIELD_HAS_REGISTRATION
        ];

        foreach ($requiredKeys as $k) {
            if (!array_key_exists($k, $data)) {
                $checkResult->add(CheckMessage::withError("$k is required"));
            }
        }

        CheckResultExceptionTranslator::tryThrowBadRequestException($checkResult);
    }
}
