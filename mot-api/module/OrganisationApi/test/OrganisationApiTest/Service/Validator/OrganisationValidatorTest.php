<?php
namespace OrganisationApiTest\Service\Validator;

use DvsaCommon\Constants\OrganisationType;
use DvsaCommon\Enum\CompanyTypeName;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use OrganisationApi\Service\Validator\OrganisationValidator;

/**
 * Class OrganisationValidatorTest
 */
class OrganisationValidatorTest extends AbstractServiceTestCase
{
    public function testValidatePassThrough()
    {
        $input = [
            'organisationName' => 'organisationName',
            'organisationType' => OrganisationType::EXAMINING_BODY,
            'companyType' => CompanyTypeName::LIMITED_LIABILITY_PARTNERSHIP,
        ];

        $this->createOrganisationValidator()->validate($input);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testValidateMissingRequiredFieldsShouldThrowException()
    {
        $this->createOrganisationValidator()->validate([]);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testValidateEmptyOrganisationTypeShouldThrowException()
    {
        $input = [
            'organisationName' => 'organisationName',
            'organisationType' => '',
            'companyType' => '',
        ];

        $this->createOrganisationValidator()->validate($input);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testValidateInvalidOrganisationTypeShouldThrowException()
    {
        $input = [
            'organisationName' => 'organisationName',
            'organisationType' => 'someOrgType',
            'companyType' => 'someCompType',
        ];

        $this->createOrganisationValidator()->validate($input);
    }

    /**
     * @return OrganisationValidator
     */
    private function createOrganisationValidator()
    {
        return new OrganisationValidator();
    }
}
