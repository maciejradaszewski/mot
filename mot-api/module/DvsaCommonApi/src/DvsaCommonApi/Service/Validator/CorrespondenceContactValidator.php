<?php
namespace DvsaCommonApi\Service\Validator;

use DvsaCommon\Utility\ArrayUtils;

/**
 * Class CorrespondenceContactValidator
 */
class CorrespondenceContactValidator extends AbstractValidator implements ValidatorInterface
{
    const ERROR_EMAIL_CONFIRMATION = 'Email address and Confirm email address must match';
    const ERROR_EMAIL_INCORRECT_FORMAT = "Incorrect email address format";

    private $contactValidator;

    public function __construct()
    {
        parent::__construct();

        $this->contactValidator = new ContactDetailsValidator(new AddressValidator());
    }

    public function validate(array $data)
    {
        $correspondencePrefix = 'correspondence';
        $correspondenceData = ArrayUtils::removePrefixFromKeys($data, $correspondencePrefix);

        $this->contactValidator->validate($correspondenceData);

        $this->errors->throwIfAny();
    }
}
