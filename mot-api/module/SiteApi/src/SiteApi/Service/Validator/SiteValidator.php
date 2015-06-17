<?php

namespace SiteApi\Service\Validator;

use DvsaCommon\Constants\FacilityTypeCode;
use DvsaCommonApi\Service\Validator\AbstractValidator;
use DvsaCommonApi\Service\Validator\ValidatorInterface;
use DvsaEntities\Entity\Vehicle;

/**
 * Class SiteValidator
 */
class SiteValidator extends AbstractValidator implements ValidatorInterface
{
    private $requiredFields
        = [
            'name'
        ];

    /** todo wk: this should be extracted to site facilities validator */
    public function validateFacilities($data)
    {
        $testClasses = $data['roles'];
        $facilities = $data['facilities'];

        // The only facility type allowable for classes 1&2 is TPTL.
        if (in_array(Vehicle::VEHICLE_CLASS_1, $testClasses)
            && in_array(Vehicle::VEHICLE_CLASS_2, $testClasses)
            && count($testClasses) == 2
        ) {
            if (
                isset($facilities[FacilityTypeCode::AUTOMATED_TEST_LANE])
                || isset($facilities[FacilityTypeCode::ONE_PERSON_TEST_LANE])
            ) {
                $this->errors->add(
                    'If a site is class 1&2 the only testing facility type that can be added is ' .
                    FacilityTypeCode::TWO_PERSON_TEST_LANE . '.'
                );
            }

            if (!isset($facilities[FacilityTypeCode::TWO_PERSON_TEST_LANE])) {
                $this->errors->add(
                    'If a site is class 1&2 the following testing facility type needs to be present: ' .
                    FacilityTypeCode::TWO_PERSON_TEST_LANE . '.'
                );
            }
        }

        $this->errors->throwIfAny();
    }

    public function validate(array $data)
    {
        $this->validateValuesOfRequiredFields($this->requiredFields, $data);
    }
}
