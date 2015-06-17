<?php

namespace Account\Factory\Controller;

use Account\Controller\PasswordResetController;
use Account\Service\PasswordResetService;
use DvsaClient\MapperFactory;
use DvsaCommon\Obfuscate\Factory\ParamObfuscatorFactory;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class PasswordResetControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new PasswordResetController(
            $serviceLocator->get(PasswordResetService::class),
            $serviceLocator->get(UserAdminSessionManager::class),
            $serviceLocator->get(MapperFactory::class),
            $serviceLocator->get('config'),
            $serviceLocator->get(ParamObfuscatorFactory::class)
        );
    }
}
