<?php

namespace DvsaCommon\Validator;


use DvsaCommon\Date\DateUtils;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class DateOfBirthValidator extends AbstractValidator
{
    const FIELD_DAY = 'day';
    const FIELD_MONTH = 'month';
    const FIELD_YEAR  = 'year';

    const ERR_MSG_IS_EMPTY = 'isEmpty';
    const ERR_MSG_IS_FUTURE	= 'isFuture';
    const ERR_MSG_IS_OVER100 ='isOver100';
    const ERR_MSG_IS_NOT_INVALID_FORMAT = 'isNotValidFormat';

    protected $messageTemplates = array(
        self::ERR_MSG_IS_EMPTY                  => 'you must enter a date of birth',
        self::ERR_MSG_IS_FUTURE                 => 'must be in the past',
        self::ERR_MSG_IS_OVER100                => 'must be less than 100 years ago',
        self::ERR_MSG_IS_NOT_INVALID_FORMAT     => 'must be a valid date of birth',
    );

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($value)
    {
        try {
            if (
                empty($value[self::FIELD_DAY]) &&
                empty($value[self::FIELD_MONTH]) &&
                empty($value[self::FIELD_YEAR])
            ) {
                $this->error(self::ERR_MSG_IS_EMPTY);
                return false;
            }

            $convertedDate = DateUtils::toDateFromParts(
                $value[self::FIELD_DAY],
                $value[self::FIELD_MONTH],
                $value[self::FIELD_YEAR]
            );

            $oneDayAgo = new \DateTime('-1 day');
            $hundredYearsAgo = new \DateTime('-100 years');

            if ($convertedDate > $oneDayAgo) {
                $this->error(self::ERR_MSG_IS_FUTURE);
                return false;
            }

            if ($convertedDate < $hundredYearsAgo) {
                $this->error(self::ERR_MSG_IS_OVER100);
                return false;
            }

        } catch (\Exception $e) {
            $this->error(self::ERR_MSG_IS_NOT_INVALID_FORMAT);
            return false;
        }

        return true;
    }
}