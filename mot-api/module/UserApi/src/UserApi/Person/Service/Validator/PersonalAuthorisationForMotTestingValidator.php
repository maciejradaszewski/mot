<?php

namespace UserApi\Person\Service\Validator;

use DvsaCommonApi\Service\Validator\AbstractValidator;
use UserApi\Person\Service\PersonalAuthorisationForMotTestingService;

/**
 * Class PersonalAuthorisationForMotTestingValidator
 *
 * @package UserApi\Person\Service\Validator
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
