<?php

namespace OrganisationApi\Service\Validator;

use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApi\Service\Validator\AbstractValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use OrganisationApi\Service\AuthorisedExaminerService;

/**
 * Class AuthorisedExaminerValidator
 */
class AuthorisedExaminerValidator extends AbstractValidator
{

    private $organisationValidator;
    private $contactDetailsValidator;

    public function __construct(
        OrganisationValidator $organisationValidator,
        ContactDetailsValidator $contactDetailsValidator
    ) {
        parent::__construct();
        $this->organisationValidator   = $organisationValidator;
        $this->contactDetailsValidator = $contactDetailsValidator;
    }

    public function validate($data)
    {
        $this->validateOrganisation($data);
        $this->validateContactDetails($data);

        if (empty($data[AuthorisedExaminerService::FIELD_CORRESPONDENCE_CONTACT_DETAILS_SAME])) {
            $correspondenceData = ArrayUtils::removePrefixFromKeys($data, 'correspondence');

            $this->validateContactDetails($correspondenceData);
        }

        $this->errors->throwIfAny();
    }

    private function validateOrganisation($data)
    {
        try {
            $this->organisationValidator->validate($data);
        } catch (BadRequestException $exception) {
            $this->errors->addException($exception);
        }
    }

    private function validateContactDetails($data)
    {
        try {
            $this->contactDetailsValidator->validate($data);
        } catch (BadRequestException $exception) {
            $this->errors->addException($exception);
        }
    }
}
