<?php


namespace DvsaMotTest\NewVehicle\Form\Validator;


use DvsaCommon\Model\CountryOfRegistration;
use Zend\I18n\Filter\Alnum;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;
use DvsaCommon\Messages\Vehicle\CreateVehicleErrors as Errors;


class NewVehicleEmptyVrmReasonValidator extends AbstractValidator
{
    const MSG_EMPTY_VRM_REASON_REQUIRED = 'MSG_REG_AND_REASON_EMPTY';
    const MSG_EMPTY_VRM_REASON_NOT_PERMITED = 'MSG_EMPTY_VRM_REASON_NOT_PERMITED';

    protected $messageTemplates = [
        self::MSG_EMPTY_VRM_REASON_REQUIRED => Errors::EMPTY_REG_REASON_REQUIRED,
        self::MSG_EMPTY_VRM_REASON_NOT_PERMITED => Errors::EMPTY_REG_REASON_NOT_PERMITTED

    ];

    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        $isVrmEmpty = strlen($context['registrationNumber']) === 0;
        if (strlen($value) === 0 || !in_array($value, $this->getOption('reasons'))) {
            if ($isVrmEmpty) {
                $this->error(self::MSG_EMPTY_VRM_REASON_REQUIRED);

                return false;
            }
        } else {
            if (!$isVrmEmpty) {
                $this->error(self::MSG_EMPTY_VRM_REASON_NOT_PERMITED);

                return false;
            }
        }

        return true;
    }
}
