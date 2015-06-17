<?php

namespace OrganisationApi\Service\Validator;

use DvsaCommonApi\Service\Validator\AddressValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaCommonApi\Service\Validator\ValidationChain;

/**
 * Builds a validator for a Authorised Examiner
 * @deprecated Possible, because now it not used anythere
 */
class AeValidatorBuilder
{
    /**
     * @param int $personId Depending on this person's permissions different data will be validated.
     *
     * @return ValidationChain
     */
    public function buildValidator($personId)
    {
        $validationChain = new ValidationChain();

        if ($this->canEditAeDetails($personId)) {
            $aeDetailsValidator = new AuthorisedExaminerDetailsValidator();
            $aeDetailsValidator->setBusinessTypeValidation($this->canEditBusinessType($personId));
            $validationChain->addValidator($aeDetailsValidator);
        }
        if ($this->canEditAeBusinessAddress($personId)) {
            $validationChain->addValidator(new ContactDetailsValidator(new AddressValidator()));
        }

        return $validationChain;
    }

    private function canEditAeDetails($personId)
    {
        return true;
    }

    private function canEditAeBusinessAddress($personId)
    {
        return true;
    }

    private function canEditBusinessType($personId)
    {
        return false;
    }
}
