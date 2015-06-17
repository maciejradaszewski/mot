<?php


namespace DvsaMotTest\NewVehicle\Form\Validator;


use Zend\I18n\Filter\Alnum;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;
use DvsaCommon\Messages\Vehicle\CreateVehicleErrors as Errors;


class NewVehicleEmptyVinReasonValidator extends AbstractValidator
{
    const MSG_EMPTY_VIN_REASON_REQUIRED = 'MSG_VIN_AND_REASON_EMPTY';
    const MSG_EMPTY_VIN_REASON_NOT_PERMITED = 'MSG_EMPTY_VIN_REASON_NOT_PERMITED';

    protected $messageTemplates = [
        self::MSG_EMPTY_VIN_REASON_REQUIRED => Errors::EMPTY_VIN_REASON_REQUIRED,
        self::MSG_EMPTY_VIN_REASON_NOT_PERMITED => Errors::EMPTY_VIN_REASON_NOT_PERMITTED

    ];

    public function isValid($value, $context = null)
    {
        $this->setValue($value);

        $isVinEmpty = strlen($context['VIN']) === 0;
        if (strlen($value) === 0 || !in_array($value, $this->getOption('reasons'))) {
            if ($isVinEmpty) {
                $this->error(self::MSG_EMPTY_VIN_REASON_REQUIRED);

                return false;
            }
        } else {
            if (!$isVinEmpty) {
                $this->error(self::MSG_EMPTY_VIN_REASON_NOT_PERMITED);

                return false;
            }
        }


        return true;
    }
}
