<?php

namespace Account\Factory\Service;

use Account\Service\PasswordResetService;
use DvsaClient\MapperFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PasswordResetServiceFactory
 * @package Account\Factory\Service
 */
class PasswordResetServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PasswordResetService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PasswordResetService(
            $serviceLocator->get(MapperFactory::class)
        );
    }
}
