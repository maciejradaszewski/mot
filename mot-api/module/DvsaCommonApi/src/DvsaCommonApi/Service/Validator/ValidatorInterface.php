<?php

namespace DvsaCommonApi\Service\Validator;

/**
 * Apparently AbstractValidator doesn't have validate method.
 *
 * Interface ValidatorInterface
 *
 * @package DvsaCommonApi\Service\Validator
 */
interface ValidatorInterface
{
    public function validate(array $data);
}
