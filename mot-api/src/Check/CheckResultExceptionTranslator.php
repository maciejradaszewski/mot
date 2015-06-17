<?php


namespace Api\Check;

use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApi\Service\Exception\DataValidationException;
use DvsaCommonApi\Service\Exception\ForbiddenException;

/**
 * Class CheckResultExceptionTranslator
 *
 * @package Api\Check
 */
class CheckResultExceptionTranslator
{
    /**
     * * Throws an exception if there are messages on CheckResult that match
     * a specified severity level(s). If the match is unsuccesful, no operation is taken.
     *
     * @param CheckResult $result
     * @param string      $severityLevel
     *
     * @throws \DvsaCommonApi\Service\Exception\DataValidationException
     */
    public static function tryThrowDataValidationException(CheckResult $result, $severityLevel = Severity::ERROR)
    {
        $checkMessages = $result->getMessagesOfSeverity($severityLevel);
        if (!empty($checkMessages)) {
            $ex = DataValidationException::create();
            foreach ($checkMessages as $message) {
                $ex->addError($message->getText(), $message->getCode(), $message->getText());
            }
            throw $ex;
        }
    }

    /**
     * @param CheckResult $result
     * @param string      $severityLevel
     *
     * @throws \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public static function tryThrowBadRequestException(CheckResult $result, $severityLevel = Severity::ERROR)
    {
        $checkMessages = $result->getMessagesOfSeverity($severityLevel);
        if (!empty($checkMessages)) {
            $ex = BadRequestException::create();
            foreach ($checkMessages as $message) {
                $ex->addError($message->getText(), $message->getCode(), $message->getText());
            }
            throw $ex;
        }
    }

    /**
     * @param CheckResult $result
     * @param string      $severityLevel
     *
     * @throws \DvsaCommonApi\Service\Exception\ForbiddenException
     */
    public static function tryThrowForbiddenException(CheckResult $result, $severityLevel = Severity::ERROR)
    {
        $checkMessages = $result->getMessagesOfSeverity($severityLevel);
        if (!empty($checkMessages)) {
            $ex = new ForbiddenException("");
            $ex->clearErrors();
            foreach ($checkMessages as $message) {
                $ex->addError($message->getText(), $message->getCode(), $message->getText());
            }
            throw $ex;
        }
    }
}
