<?php

namespace DvsaCommonApi\Factory\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommonApi\Listener\RestUnauthorizedStrategy;

/**
 * Class UnauthorizedStrategyFactory
 * @package DvsaCommonApi\Factory\Listener
 */
class UnauthorizedStrategyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RestUnauthorizedStrategy($serviceLocator->get('DvsaAuthenticationService'));
    }
}
