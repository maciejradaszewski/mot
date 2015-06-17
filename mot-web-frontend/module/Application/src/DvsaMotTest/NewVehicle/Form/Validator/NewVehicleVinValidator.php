<?php


namespace DvsaMotTest\NewVehicle\Form\Validator;


use DvsaCommon\Model\CountryOfRegistration;
use Zend\I18n\Validator\Alnum;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;
use DvsaCommon\Messages\Vehicle\CreateVehicleErrors as Errors;


class NewVehicleVinValidator extends AbstractValidator
{

    const MSG_MAX_VIN_LENGTH_EXCEEDED = 'MSG_MAX_VIN_LENGTH_EXCEEDED';
    const MSG_VIN_AND_REASON_EMPTY = 'MSG_VIN_AND_REASON_EMPTY';
    const MSG_BOTH_REG_VIN_EMPTY = 'MSG_BOTH_REG_VIN_EMPTY';
    const MSG_VIN_INVALID = 'MSG_VIN_VALID';

    const LIMIT_VIN_MAX = 20;


    protected $messageTemplates = [
        self::MSG_MAX_VIN_LENGTH_EXCEEDED => Errors::VIN_LENGTH,
        self::MSG_VIN_INVALID => Errors::VIN_INVALID,
        self::MSG_VIN_AND_REASON_EMPTY => Errors::VIN_EMPTY,
        self::MSG_BOTH_REG_VIN_EMPTY => Errors::BOTH_REG_AND_VIN_EMPTY
    ];


    public function isValid($value, $context = null)
    {
        $this->setValue($value);

        if (strlen($value) === 0) {
            if (strlen($context['emptyVinReason']) === 0) {
                $this->error(self::MSG_VIN_AND_REASON_EMPTY);

                return false;
            } else {
                if (strlen($context['registrationNumber']) === 0 && strlen($context['emptyVrmReason']) > 0) {
                    $this->error(self::MSG_BOTH_REG_VIN_EMPTY);

                    return false;
                }
            }
        } elseif (strlen($value) > self::LIMIT_VIN_MAX) {
            $this->error(self::MSG_MAX_VIN_LENGTH_EXCEEDED);

            return false;
        } else {
            if (false === (new Alnum())->isValid($value)) {
                $this->error(self::MSG_VIN_INVALID);

                return false;
            }
        }

        return true;
    }
}
