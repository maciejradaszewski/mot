<?php

namespace DvsaCommonApi\Service\Exception;

/**
 * Class ForbiddenException
 *
 * @package DvsaCommonApi\Service\Exception
 */
class ForbiddenException extends ServiceException
{
    const ERROR_CODE_FORBIDDEN = 403;

    /**
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, self::ERROR_CODE_FORBIDDEN);

        $this->addError($message, self::ERROR_CODE_FORBIDDEN, $message);
    }

    /**
     * Creates a new exception without any errors attached
     * @param $message
     *
     * @return ForbiddenException
     */
    public static function createEmpty($message)
    {
        $exception = new ForbiddenException($message);
        $exception->clearErrors();
        return $exception;
    }
}
