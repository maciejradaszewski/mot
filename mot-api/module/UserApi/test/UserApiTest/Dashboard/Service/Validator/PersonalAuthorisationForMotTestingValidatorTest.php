<?php

namespace UserApiTest\Service\Validator;

use UserApi\Person\Service\Validator\PersonalAuthorisationForMotTestingValidator;

/**
 * unit tests for PersonalAuthorisationForMotTestingValidator
 */
class PersonalAuthorisationForMotTestingValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function test_validate_shouldBeOk()
    {
        $validator = new PersonalAuthorisationForMotTestingValidator();

        $data = [
            'result' => 1,
            'group'  => 1
        ];
        $validator->validate($data);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function test_validate_invalidGroupOfVehicle_shouldThrowBadRequestException()
    {
        $validator = new PersonalAuthorisationForMotTestingValidator();

        $data = [
            'result' => 1,
            'group'  => 3
        ];
        $validator->validate($data);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\RequiredFieldException
     */
    public function test_validate_emptyInput_shouldThrowBadRequestException()
    {
        $validator = new PersonalAuthorisationForMotTestingValidator();

        $data = [];
        $validator->validate($data);
    }
}
