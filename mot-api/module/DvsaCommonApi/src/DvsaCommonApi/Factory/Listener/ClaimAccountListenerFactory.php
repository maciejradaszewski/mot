<?php

namespace DvsaCommonApi\Factory\Listener;

use DvsaCommon\Auth\MotIdentityProviderInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommonApi\Listener\ClaimAccountListener;

class ClaimAccountListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ClaimAccountListener($serviceLocator->get(MotIdentityProviderInterface::class));
    }
}
