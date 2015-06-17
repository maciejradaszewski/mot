<?php
namespace OrganisationApiTest\Service\Validator;

use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use OrganisationApi\Service\Validator\AuthorisedExaminerValidator;
use OrganisationApi\Service\Validator\OrganisationValidator;

/**
 * Class AuthorisedExaminerValidatorTest
 */
class AuthorisedExaminerValidatorTest extends AbstractServiceTestCase
{
    public function testValidatePassThrough()
    {
        $validator = new AuthorisedExaminerValidator(
            $this->getMockWithDisabledConstructor(OrganisationValidator::class),
            $this->getMockWithDisabledConstructor(ContactDetailsValidator::class)
        );

        $validator->validate([]);
    }
}
