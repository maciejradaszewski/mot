<?php

namespace Dashboard\Factory\Authorisation;

use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewTradeRolesAssertionFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ViewTradeRolesAssertion(
            $serviceLocator->get('AuthorisationService'),
            $serviceLocator->get('MotIdentityProvider')
        );
    }
}
