<?php

namespace SiteApi\Service\Validator;

use DvsaCommonApi\Service\Validator\ValidationChain;
use DvsaCommonApi\Service\Validator\AddressValidator;
use DvsaCommonApi\Service\Validator\ContactDetailsValidator;
use DvsaCommonApi\Service\Validator\CorrespondenceContactValidator;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;

/**
 * Builds a validator for a site
 *
 * Class SiteValidatorBuilder
 *
 * @package SiteApi\Service\Validator
 */
class SiteValidatorBuilder
{
    /**
     * @var UpdateVtsAssertion
     */
    private $updateVtsAssertion;

    public function __construct(UpdateVtsAssertion $updateVtsAssertion)
    {
        $this->updateVtsAssertion = $updateVtsAssertion;
    }

    /**
     * @return ValidationChain
     */
    public function buildEditValidator($siteId)
    {
        $validationChain = new ValidationChain();

        if ($this->updateVtsAssertion->canUpdateBusinessDetails($siteId)) {
            $validationChain->addValidator(new ContactDetailsValidator(new AddressValidator()));
        }

        if ($this->updateVtsAssertion->canUpdateCorrespondenceDetails($siteId)) {
            $validationChain->addValidator(new CorrespondenceContactValidator());
        }

        return $validationChain;
    }
}
