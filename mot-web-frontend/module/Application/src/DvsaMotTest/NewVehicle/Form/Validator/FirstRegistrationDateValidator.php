<?php

namespace DvsaMotTest\NewVehicle\Form\Validator;

use DvsaCommon\Date\DateUtils;
use DvsaCommon\Messages\Vehicle\CreateVehicleErrors as Errors;
use Zend\Validator\AbstractValidator;

/**
 * Class FirstRegistrationDateValidator
 */
class FirstRegistrationDateValidator extends AbstractValidator
{
    const MIN_DATE = '1800-01-01';

    const FUTURE   = 'future';
    const OLD_PAST = 'old_past';
    const INVALID  = 'invalid';
    const IS_EMPTY = 'empty';

    protected $messageTemplates = [
        self::FUTURE   => Errors::DATE_MAX,
        self::OLD_PAST => Errors::DATE_MIN,
        self::INVALID  => Errors::DATE_INVALID,
        self::IS_EMPTY => Errors::DATE_EMPTY,

    ];

    public function isValid($value)
    {
        $this->setValue($value);

        if (!$value || !isset($value['year']) || !isset($value['month']) || !isset($value['day'])) {
            $this->error(self::INVALID);
            return false;
        }

        if (
            empty($value['year']) &&
            empty($value['month']) &&
            empty($value['day'])
        ) {
            $this->error(self::IS_EMPTY);
            return false;
        }
        $fullDateString = ($value['year'] . '-' . $value['month'] . '-' . $value['day']);

        try {
            $date = DateUtils::toDate($fullDateString);

            if ($date < date_create(self::MIN_DATE)) {
                $this->error(self::OLD_PAST);
                return false;
            } elseif (DateUtils::isDateInFuture($date)) {
                $this->error(self::FUTURE);
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            $this->error(self::INVALID);
            return false;
        }
    }
}
