<?php

namespace UserApi\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UserApi\Person\Service\Validator\BasePersonValidator;

class BasePersonValidatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new BasePersonValidator();
    }
}
