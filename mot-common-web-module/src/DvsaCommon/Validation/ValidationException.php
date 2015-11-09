<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\Validation;

use RuntimeException;

/**
 * ValidationException holds the result of a validator pass.
 */
class ValidationException extends RuntimeException
{
    /**
     * @var ValidationResult
     */
    private $validationResult;

    /**
     * ValidationException constructor.
     *
     * @param ValidationResult $validationResult
     */
    public function __construct(ValidationResult $validationResult)
    {
        $this->validationResult = $validationResult;
    }

    /**
     * Return a list of validation failure messages.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->validationResult->getMessages();
    }

    /**
     * Return a flattened list of validation failure messages. If a field has more than one message only the first one
     * will be returned.
     *
     * @return array
     */
    public function getInlineMessages()
    {
        return $this->validationResult->getFlattenedMessages();
    }
}
