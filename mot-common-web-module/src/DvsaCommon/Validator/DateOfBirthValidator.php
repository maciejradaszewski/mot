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

    const IS_EMPTY = 'isEmpty';
    const IS_FUTURE	= 'isFuture';
    const IS_OVER100 ='isOver100';
    const IS_INVALID_FORMAT = 'isNotValidFormat';

    const ERR_MSG_IS_EMPTY = 'you must enter a date of birth';
    const ERR_MSG_IS_FUTURE	= 'must be in the past';
    const ERR_MSG_IS_OVER100 = 'must be less than 100 years ago';
    const ERR_MSG_IS_INVALID_FORMAT = 'must be a valid date of birth';

    protected $messageTemplates = array(
        self::IS_EMPTY          => self::ERR_MSG_IS_EMPTY,
        self::IS_FUTURE         => self::ERR_MSG_IS_FUTURE,
        self::IS_OVER100        => self::IS_OVER100,
        self::IS_INVALID_FORMAT => self::ERR_MSG_IS_INVALID_FORMAT,
    );

    private $dateInThePast;

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
        $value = $this->prepareValue($value);

        try {
            if (
                empty($value[self::FIELD_DAY]) &&
                empty($value[self::FIELD_MONTH]) &&
                empty($value[self::FIELD_YEAR])
            ) {
                $this->error(self::IS_EMPTY);
                return false;
            }

            $convertedDate = DateUtils::toDateFromParts(
                $value[self::FIELD_DAY],
                $value[self::FIELD_MONTH],
                $value[self::FIELD_YEAR]
            );

            $oneDayAgo = new \DateTime('-1 day');
            $hundredYearsAgo = $this->getDateInThePast();

            if ($convertedDate > $oneDayAgo) {
                $this->error(self::IS_FUTURE);
                return false;
            }

            if ($convertedDate < $hundredYearsAgo) {
                $this->error(self::IS_OVER100);
                return false;
            }

        } catch (\Exception $e) {
            $this->error(self::IS_INVALID_FORMAT);
            return false;
        }

        return true;
    }

    private function prepareValue($value)
    {
        if (is_array($value)) {
            return $value;
        }

        $values = explode("-", $value);
        $values = array_pad($values, 3, 0);
        return array_combine([self::FIELD_YEAR, self::FIELD_MONTH, self::FIELD_DAY], $values);

    }

    public function setDateInThePast(\DateTime $date) {
        $this->dateInThePast = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateInThePast()
    {
        if ($this->dateInThePast === null) {
            $this->dateInThePast = new \DateTime('-100 years');
            return $this->dateInThePast;
        }

        return $this->dateInThePast;
    }
}