<?php
namespace DvsaCommonApi\Service\Validator;

use DvsaCommonApi\Service\Validator\AbstractValidator;
use DvsaCommonApi\Service\Validator\ValidatorInterface;

/**
 * Class AddressValidator
 *
 * @package DvsaCommonApi\Service\Validator
 */
class AddressValidator extends AbstractValidator implements ValidatorInterface
{
    private $requiredFields = [
        'addressLine1',
        'town',
        'postcode',
    ];

    public function validate(array $data)
    {
        $this->checkRequiredFields($this->requiredFields, $data);
    }
}
