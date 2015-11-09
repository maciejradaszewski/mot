<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommon\Validation;

/**
 * Represents the result of a Validator pass.
 */
class ValidationResult
{
    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var array
     */
    private $messages;

    /**
     * ValidationResult constructor.
     *
     * @param bool  $isValid
     * @param array $messages
     */
    public function __construct($isValid, array $messages = [])
    {
        $this->isValid = $isValid;
        $this->messages = $messages;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return (bool) $this->isValid;
    }

    /**
     * Return a list of validation failure messages.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Return a flattened list of validation failure messages. If a field has more than one message only the first one
     * will be returned.
     *
     * @return array
     */
    public function getFlattenedMessages()
    {
        $flattenedMessages = [];
        foreach ($this->messages as $field => $messages) {
            $flattenedMessages[$field] = array_shift($messages);
        }

        return $flattenedMessages;
    }
}
