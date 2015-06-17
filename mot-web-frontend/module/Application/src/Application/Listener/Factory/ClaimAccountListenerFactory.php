<?php

namespace Application\Listener\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Listener\ClaimAccountListener;

class ClaimAccountListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ClaimAccountListener($serviceLocator->get('MotIdentityProvider'));
    }
}
