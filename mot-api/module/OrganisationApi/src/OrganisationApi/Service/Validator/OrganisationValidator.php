<?php
namespace OrganisationApi\Service\Validator;

use DvsaCommon\Enum\CompanyTypeName;
use DvsaCommonApi\Service\Exception\RequiredFieldException;
use DvsaCommonApi\Service\Validator\AbstractValidator;

/**
 * Class OrganisationValidator
 */
class OrganisationValidator extends AbstractValidator
{
    const ERROR_INVALID_COMPANY_TYPE = "'%s' is not a valid AE company type";
    const ERROR_EMPTY_COMPANY_TYPE   = 'AE company type should not be empty';

    private $requiredFields
        = [
            'organisationName',
            'areaOfficeNumber'
        ];

    public function validate($data)
    {
        try {
            $this->checkRequiredFields($this->requiredFields, $data);

            if (!CompanyTypeName::exists($data['companyType'])) {
                if (empty($data['companyType'])) {
                    $this->errors->add(self::ERROR_EMPTY_COMPANY_TYPE, 'companyType');
                } else {
                    $error = sprintf(self::ERROR_INVALID_COMPANY_TYPE, $data['companyType']);
                    $this->errors->add($error, 'companyType');
                }
            }
        } catch (RequiredFieldException $e) {
            $this->errors->addException($e);
        }

        $this->errors->throwIfAny();
    }
}
