<?php

namespace OrganisationApiTest\Service\Validator;

use OrganisationApi\Service\Validator\AuthorisedExaminerDetailsValidator;

/**
 * unit tests for AuthorisedExaminerDetailsValidator
 */
class AuthorisedExaminerDetailsValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $validator = new AuthorisedExaminerDetailsValidator();
        $validator->validate(['organisationName' => 'aaa']);
    }

    public function test_validate_withOrganisationType()
    {
        $validator = new AuthorisedExaminerDetailsValidator();
        $validator->setBusinessTypeValidation(true);
        $validator->validate(['organisationName' => 'aaa', 'organisationType' => 'aaa']);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\RequiredFieldException
     */
    public function test_validate_shouldThrowException()
    {
        $validator = new AuthorisedExaminerDetailsValidator();
        $validator->validate([]);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\RequiredFieldException
     */
    public function test_validate_with_orgType_shouldThrowException()
    {
        $validator = new AuthorisedExaminerDetailsValidator();
        $validator->setBusinessTypeValidation(true);
        $validator->validate(['organisationName' => 'aaa']);
    }
}
