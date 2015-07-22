<?php

namespace DvsaClient\ViewModel;

use Zend\Stdlib\Parameters;

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

    public function clearEmpty(Parameters $params)
    {
        $callback = function (&$item) use (&$callback) {
            if (is_array($item)) {
                $item = array_filter($item, $callback);
            }

            return !empty($item);
        };

        $params->fromArray(array_filter($params->toArray(), $callback));
    }
}
