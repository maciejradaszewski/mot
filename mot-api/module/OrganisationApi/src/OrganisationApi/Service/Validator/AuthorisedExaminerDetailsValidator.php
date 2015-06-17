<?php

namespace OrganisationApi\Service\Validator;

use DvsaCommonApi\Service\Validator\AbstractValidator;
use DvsaCommonApi\Service\Validator\ValidatorInterface;

/**
 * Validates organisationName and (not always - depends on person permissions) organisationType
 */
class AuthorisedExaminerDetailsValidator extends AbstractValidator implements ValidatorInterface
{

    protected $requiredFields       = ['organisationName'];
    private $businessTypeValidation = false;

    public function validate(array $data)
    {
        if ($this->businessTypeValidation) {
            $this->requiredFields[] = 'organisationType';
        }

        $this->checkRequiredFields($this->requiredFields, $data);

        $this->errors->throwIfAny();
    }

    /**
     * Set true if person allowed to update `organisationType`
     *
     * @param bool $value
     *
     * @return AuthorisedExaminerDetailsValidator
     */
    public function setBusinessTypeValidation($value)
    {
        $this->businessTypeValidation = $value;

        return $this;
    }
}
