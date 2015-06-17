<?php

namespace Application\Listener\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Listener\ChangeTempPasswordListener;

class ChangeTempPasswordListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ChangeTempPasswordListener($serviceLocator->get('MotIdentityProvider'));
    }
}
