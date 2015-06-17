<?php


namespace DvsaMotTest\NewVehicle\Form\Validator;


use DvsaCommon\Model\CountryOfRegistration;
use Zend\I18n\Validator\Alnum;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;
use DvsaCommon\Messages\Vehicle\CreateVehicleErrors as Errors;


class NewVehicleVrmValidator extends AbstractValidator
{

    const MSG_MAX_VRM_LENGTH_EXCEEDED = 'MSG_MAX_VRM_LENGTH_EXCEEDED';
    const MSG_REG_AND_REASON_EMPTY = 'MSG_REG_AND_REASON_EMPTY';
    const MSG_REG_INVALID = 'MSG_REG_VALID';
    const MSG_BOTH_REG_VIN_EMPTY = 'MSG_BOTH_REG_VIN_EMPTY';


    const LIMIT_REG_MAX = 13;
    const LIMIT_REG_UK_MAX = 7;


    protected $messageTemplates = [
        self::MSG_MAX_VRM_LENGTH_EXCEEDED => '',
        self::MSG_REG_AND_REASON_EMPTY => Errors::REG_EMPTY,
        self::MSG_REG_INVALID => Errors::REG_INVALID,
        self::MSG_BOTH_REG_VIN_EMPTY => Errors::BOTH_REG_AND_VIN_EMPTY
    ];


    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        $cor = $context['countryOfRegistration'];
        $isUkCountry = CountryOfRegistration::isUkCountry($cor);
        if (strlen($cor) === 0) {
            $maxVRMMessage = Errors::REG_TOO_LONG_NO_COUNTRY;
        } else {
            $maxVRMMessage = $isUkCountry ? Errors::REG_TOO_LONG_FOR_UK : Errors::REG_TOO_LONG;
        }
        $maxVRMLength = $isUkCountry ? self::LIMIT_REG_UK_MAX : self::LIMIT_REG_MAX;

        if (strlen($value) === 0) {
            if (strlen($context['emptyVrmReason']) === 0) {
                $this->error(self::MSG_REG_AND_REASON_EMPTY);

                return false;
            } else {
                if (strlen($context['VIN']) === 0 && strlen($context['emptyVinReason']) > 0) {
                    $this->error(self::MSG_BOTH_REG_VIN_EMPTY);

                    return false;
                }
            }
        } elseif (strlen($value) > $maxVRMLength) {
            $this->setMessage(sprintf($maxVRMMessage, $maxVRMLength), self:: MSG_MAX_VRM_LENGTH_EXCEEDED);
            $this->error(self::MSG_MAX_VRM_LENGTH_EXCEEDED);

            return false;
        } else {
            if (false === (new Alnum())->isValid($value)) {
                $this->error(self::MSG_REG_INVALID);

                return false;
            }
        }

        return true;
    }
}
