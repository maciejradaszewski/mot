<?php

namespace DvsaAuthentication\Factory;

use DvsaAuthentication\IdentityProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class IdentityProviderFactory
 *
 * @package DvsaAuthentication\Factory
 */
class IdentityProviderFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $identityProvider = new IdentityProvider($serviceLocator->get('DvsaAuthenticationService'));

        return $identityProvider;
    }
}
