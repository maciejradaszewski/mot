<?php
use \DvsaCommon\Utility\ArrayUtils;
use \DvsaCommon\Error\ApiErrorCodes;

class ApiErrorException extends \Exception
{
    private $errorsArray;
    private $errorData;
    private $displayMessage;

    public function getErrorsArray()
    {
        return $this->errorsArray;
    }

    public function getErrorData()
    {
        return $this->errorData;
    }

    public function getDisplayMessage()
    {
        return $this->displayMessage;
    }

    public function __construct($errors, $errorData = null)
    {
        /*
         * TODO Ditch stdClass type of returns from API calls, use array everywhere.
         * Then we won't need this round-tripping.
         */
        $errorsArray = json_decode(json_encode($errors), true);
        $this->errorsArray = $errorsArray;
        $this->errorData = $errorData;

        $error = is_array($errorsArray) ? current($errorsArray) : $errorsArray;

        if ($error) {
            if (is_string($error)) {
                $this->message = $error;
            } else {
                if (array_key_exists('message', $error)) {
                    $this->message = $error['message'];
                }
                if (array_key_exists('displayMessage', $error)) {
                    $this->displayMessage = $error['displayMessage'];
                }
                if (array_key_exists('code', $error)) {
                    $this->code = $error['code'];
                }
                if (array_key_exists('exception', $error)) {
                    $this->message = $this->message . "; Exception trace: " . implode($error['exception']);
                }
            }
        }
    }

    public function isForbiddenException()
    {
        return ArrayUtils::anyMatch(
            $this->getErrorsArray(), function ($a) {
                return $a['code'] == ApiErrorCodes::UNAUTHORISED;
            }
        );
    }
}
