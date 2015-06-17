<?php

namespace UserApi\Factory;

use UserApi\Application\Service\Validator\AccountValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AccountValidatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AccountValidator();
    }
}
