<?php

namespace DvsaClient\ViewModel;

/**
 * Contains common functionality for FORM in view
 */
abstract class AbstractFormModel
{
    private $errors = [];

    /**
     * @param array $errors
     */
    public function addErrors($errors)
    {
        $this->errors = $errors;
    }

    public function addError($fieldName, $message = null)
    {
        $this->errors[] = [
            'field'          => $fieldName,
            'displayMessage' => (string) $message,
        ];
    }

    public function addErrorAsArray(array $error)
    {
        $this->errors[] = $error;
    }

    public function getError($fieldName)
    {
        foreach ($this->errors as $error) {
            if ($error['field'] == $fieldName) {
                return $error['displayMessage'];
            }
        }
        return null;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    abstract public function isValid();
}
