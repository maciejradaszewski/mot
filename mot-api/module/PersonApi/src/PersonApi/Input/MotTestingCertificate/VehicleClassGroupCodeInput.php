<?php

namespace PersonApi\Input\MotTestingCertificate;

use DvsaCommon\Enum\VehicleClassGroupCode;
use Zend\InputFilter\Input;
use Zend\Validator\NotEmpty;
use Zend\Validator\InArray;

class VehicleClassGroupCodeInput extends Input
{
    const FIELD = 'vehicleClassGroupCode';
    const MSG_EMPTY = 'vehicle class group must not be empty';
    const MSG_NOT_EXIST = 'vehicle group class does not exist';

    private $allowedGroups = [VehicleClassGroupCode::BIKES, VehicleClassGroupCode::CARS_ETC];

    public function __construct()
    {
        parent::__construct(self::FIELD);

        $emptyValidator = (new NotEmpty())->setMessage(self::MSG_EMPTY, NotEmpty::IS_EMPTY);

        $inArrayValidator = new InArray();
        $inArrayValidator->setHaystack($this->allowedGroups);
        $inArrayValidator->setMessage(self::MSG_NOT_EXIST, InArray::NOT_IN_ARRAY);

        $this
            ->setRequired(true)
            ->getValidatorChain()
            ->attach($emptyValidator)
            ->attach($inArrayValidator)
        ;
    }
}
