<?php

namespace OrganisationApi\Service\Validator;

use DvsaCommon\Dto\Organisation\AuthorisedExaminerAuthorisationDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\AuthorisationForAuthorisedExaminerStatusCode;
use DvsaCommon\Enum\CompanyTypeCode;
use DvsaCommonApi\Service\Validator\AbstractValidator;

class AuthorisedExaminerValidator extends AbstractValidator
{
    const FIELD_ORGANISATION_NAME = 'organisationName';
    const FIELD_COMPANY_TYPE = 'companyType';
    const FIELD_REG_NUMBER= 'registeredCompanyNumber';
    const FIELD_STATUS= 'status';
    const FIELD_AO_NUMBER = 'assignedAreaOffice';

    const ERR_ORGANISATION_NAME_REQUIRE = 'A business name must be entered';
    const ERR_COMPANY_TYPE_REQUIRE = 'A business type must be selected';
    const ERR_COMPANY_NUMBER_REQUIRE = 'A company number must be entered';
    const ERR_STATUS = 'A status must be selected';
    const ERR_AO_NR_REQUIRE = 'An area office must be selected';

    /** @var ContactValidator */
    private $contactValidator;

    public function __construct($errors = null)
    {
        parent::__construct($errors);
        $this->contactValidator = new ContactValidator();
    }

    /**
     * @param OrganisationDto $organisationDto
     * @param Array $validAreaOffices
     * @throws \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function validate(OrganisationDto $organisationDto, $validAreaOffices)
    {
        //  --  Validate contact   --
        /** @var OrganisationContactDto $contactDto */
        foreach ($organisationDto->getContacts() as $contactDto) {
            $this->errors = $this->contactValidator->validate($contactDto);
        }

        $this->validateAeDetail($organisationDto, $validAreaOffices);

        $this->errors->throwIfAnyField();
    }

    private function validateAeDetail(OrganisationDto $organisationDto, $validAreaOffices)
    {
        if ($this->isEmpty($organisationDto->getName())) {
            $this->errors->add(self::ERR_ORGANISATION_NAME_REQUIRE, self::FIELD_ORGANISATION_NAME);
        }

        if ($this->isEmpty($organisationDto->getCompanyType())
            || CompanyTypeCode::exists($organisationDto->getCompanyType()) === false) {
            $this->errors->add(self::ERR_COMPANY_TYPE_REQUIRE, self::FIELD_COMPANY_TYPE);
        }


        $authForAeDto = $organisationDto->getAuthorisedExaminerAuthorisation();
        if (is_null($authForAeDto)) {
            $this->errors->add(self::ERR_AO_NR_REQUIRE, self::FIELD_AO_NUMBER);
        } else {
            $this->validateAreaOffice($authForAeDto, $validAreaOffices);
        }

        if ($organisationDto->getCompanyType() === CompanyTypeCode::COMPANY) {
            $this->validateCompanyNumber($organisationDto);
        }
    }

    private function validateAreaOfficeNumber($intAONumber, $validAreaOffices)
    {
        foreach ($validAreaOffices as $areaOffice) {
            if ((int)$intAONumber == (int)$areaOffice['areaOfficeNumber']) {
                return;
            }
        }
        $this->errors->add(self::ERR_AO_NR_REQUIRE, self::FIELD_AO_NUMBER);
    }

    private function validateCompanyNumber(OrganisationDto $organisationDto)
    {
        /**
         * If Business Type is "Company" then it needs to be verified not empty
         */
        if ($this->isEmpty($organisationDto->getRegisteredCompanyNumber())) {
            $this->errors->add(self::ERR_COMPANY_NUMBER_REQUIRE, self::FIELD_REG_NUMBER);
        }
    }

    public function validateStatus(AuthorisedExaminerAuthorisationDto $dto)
    {
        if (empty($dto->getStatus()) || $this->isEmpty($dto->getStatus()->getCode())
            || AuthorisationForAuthorisedExaminerStatusCode::exists(
                $dto->getStatus()->getCode()
            ) === false
        ) {
            $this->errors->add(self::ERR_STATUS, self::FIELD_STATUS);
        }
        return $this;
    }

    public function validateAreaOffice(AuthorisedExaminerAuthorisationDto $dto, $validAreaOffices)
    {
        $intAONumber = $dto->getAssignedAreaOffice();

        if ($this->isEmpty($intAONumber)) {
            $this->errors->add(self::ERR_AO_NR_REQUIRE, self::FIELD_AO_NUMBER);
        } else {
            $this->validateAreaOfficeNumber($intAONumber, $validAreaOffices);
        }
        return $this;
    }

    public function failOnErrors()
    {
        $this->errors->throwIfAnyField();
        return $this;
    }
}
