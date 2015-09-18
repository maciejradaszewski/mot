<?php

namespace DvsaCommon\Validator;

use DvsaCommon\Date\DateUtils;
use Zend\Validator\AbstractValidator;
use DvsaCommon\InputFilter\Event\RecordInputFilter;

class RecordEventDateValidator extends AbstractValidator
{
    const FUTURE = 'dateFuture';
    const PRE1900 = 'pre1900';
    const INVALID = 'dateInvalid';

    protected $messageTemplates = array(
        self::FUTURE      => "must not be in the future",
        self::INVALID     => "must be a valid date. For example, 31 01 2015",
        self::PRE1900     => "must not be before 1900",
    );

    /**
     * Validator assumes that you have already checked the input with Date
     * @param string $value "Y-m-d" Format string
     * @return bool
     */
    public function isValid($value)
    {
        try {
            $convertedDate = DateUtils::toDateFromParts(
                $value[RecordInputFilter::FIELD_DAY],
                $value[RecordInputFilter::FIELD_MONTH],
                $value[RecordInputFilter::FIELD_YEAR]
            );

            $now = new \DateTime();
            if ((int) $convertedDate->format('Ymd') > (int) $now->format('Ymd')) {
                $this->error(self::FUTURE);
                return false;
            }

            if ((int) $convertedDate->format('Ymd') < 19000000) {
                $this->error(self::PRE1900);
                return false;
            }

        } catch (\Exception $e) {
            $this->error(self::INVALID);
            return false;
        }

        return true;
    }
}