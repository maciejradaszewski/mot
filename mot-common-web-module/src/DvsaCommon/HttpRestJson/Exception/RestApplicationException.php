<?php

namespace DvsaCommon\HttpRestJson\Exception;

/**
 * Covers all exceptions that can actually be handled by the user.
 *
 * @package DvsaCommon\HttpRestJson\Exception
 */
class RestApplicationException extends GeneralRestException implements ExceptionInterface
{
    protected $errors;
    protected $errorData;

    public function __construct($resourcePath, $method, $postData, $statusCode, $errors = null, $errorData = null)
    {
        $this->errors = $errors;
        $this->errorData = $errorData;

        parent::__construct($resourcePath, $method, $postData, $statusCode, $this->getMessageForRequestData());
    }

    /**
     * Expand the leaves out with the referenced messages in the errors array.
     *
     * @return array returns a (potentially) complex array
     */
    public function getExpandedErrorData()
    {
        $errors = $this->getErrors();
        $errorData = $this->getErrorData();

        if (is_array($errorData) && !empty($errorData)) {
            array_walk_recursive(
                $errorData,
                function (&$item, $key, $errors) {
                    if (array_key_exists($item, $errors)) {
                        $item = array(
                            'ref'   => $item,
                            'error' => $errors[$item],
                        );
                    }
                },
                $errors
            );
        }

        return $errorData;
    }

    /**
     * Return a flat array of arrays comprising "displayMessage" and "ref"
     *
     */
    public function getFormErrorDisplayMessages()
    {
        $errorData = $this->getErrorData();
        $errors = $this->getErrors();
        $displayMessages = array();
        $inputData = array('errors' => $errors, 'output' => &$displayMessages);

        if (is_array($errorData) && !empty($errorData)) {
            array_walk_recursive(
                $errorData,
                function ($item, $key, &$userData) {
                    if (array_key_exists($item, $userData['errors'])) {
                        $userData['output'][] = array(
                            'displayMessage' => $userData['errors'][$item]['displayMessage'],
                            'ref'            => $item
                        );
                    }
                },
                $inputData
            );
        }

        return $displayMessages;
    }

    /**
     * Return displayMessages that are not part of the fieldErrors
     *
     * @return array
     */
    public function getDisplayMessages()
    {
        $displayMessages = array();
        if ($this->errors) {
            foreach ($this->errors as $error) {
                if (is_array($error) && array_key_exists('displayMessage', $error)) {
                    $displayMessages[] = $error['displayMessage'];
                } else {
                    error_log("Error without displayMessage: [" . print_r($error, true) . "]");
                }
            }
        }
        $displayMessages = array_unique($displayMessages);
        $formMessages = array();

        foreach ($this->getFormErrorDisplayMessages() as $message) {
            $formMessages[] = $message['displayMessage'];
        }

        return array_diff($displayMessages, $formMessages);
    }

    public function getMessageForRequestData()
    {
        $postDataString = json_encode($this->postData);
        $errorsString = ($this->errors) ? json_encode($this->errors) : '';

        return "resourcePath='$this->resourcePath',
                method='$this->method',
                postData='$postDataString',
                statusCode='$this->code',
                errors='$errorsString'";
    }

    /**
     * Denotes whether the exception wraps a error matching that supplied
     *
     * @param string $errorString
     * @return bool
     */
    public function containsError($errorString)
    {
        foreach ($this->getErrors() as $anError) {
            if ($anError['displayMessage'] == $errorString) {
                return true;
            }
        }
        return false;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getErrorData()
    {
        return $this->errorData;
    }
}
