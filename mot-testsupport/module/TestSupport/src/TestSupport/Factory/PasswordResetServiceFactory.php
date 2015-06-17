<?php

namespace TestSupport\Factory;

use TestSupport\Service\PasswordResetService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Helper\TestSupportRestClientHelper;

class PasswordResetServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PasswordResetService(
            $serviceLocator->get(TestSupportRestClientHelper::class)
        );
    }
}
