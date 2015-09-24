<?php

namespace PersonApi\Service\Validator;

use DvsaCommonApi\Service\Validator\AbstractValidator;
use PersonApi\Service\PersonalAuthorisationForMotTestingService;

/**
 * Class PersonalAuthorisationForMotTestingValidator
 *
 * @package PersonApi\Service\Validator
 */
class PersonalAuthorisationForMotTestingValidator extends AbstractValidator
{
    const INVALID_VEHICLE_GROUP = 'Invalid group of vehicle classes';

    private $requiredFields
        = [
            'result',
            'group',
        ];

    public function validate($data)
    {
        $this->checkRequiredFields($this->requiredFields, $data);

        $this->validateGroupOfVehicle($data);

        $this->errors->throwIfAny();
    }

    /**
     * @param $data
     */
    private function validateGroupOfVehicle($data)
    {
        if (false === in_array(
                $data['group'],
                [
                    PersonalAuthorisationForMotTestingService::GROUP_A_VEHICLE,
                    PersonalAuthorisationForMotTestingService::GROUP_B_VEHICLE,
                ]
            )
        ) {
            $this->errors->add(self::INVALID_VEHICLE_GROUP, 'group');
        }
    }
}
