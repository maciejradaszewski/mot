<?php

namespace Core\Factory;

use Core\Authorisation\Assertion\WebPerformMotTestAssertion;
use DvsaCommon\Auth\Assertion\PerformMotTestAssertion;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WebPerformMotTestAssertionFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new WebPerformMotTestAssertion(
            new PerformMotTestAssertion(
                $serviceLocator->get('AuthorisationService'),
                $serviceLocator->get('MotIdentityProvider')
            )
        );
    }
}
