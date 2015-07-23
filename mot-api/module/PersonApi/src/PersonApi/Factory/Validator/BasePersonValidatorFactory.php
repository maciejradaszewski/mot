<?php

namespace PersonApi\Factory\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use PersonApi\Service\Validator\BasePersonValidator;

class BasePersonValidatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new BasePersonValidator();
    }
}
