<?php

namespace Account\Factory\Service;

use Account\Service\SecurityQuestionService;
use DvsaClient\MapperFactory;
use DvsaCommon\Obfuscate\ParamObfuscator;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SecurityQuestionServiceFactory
 * @package Account\Factory\Service
 */
class SecurityQuestionServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SecurityQuestionService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SecurityQuestionService(
            $serviceLocator->get(MapperFactory::class),
            $serviceLocator->get(UserAdminSessionManager::class),
            $serviceLocator->get(ParamObfuscator::class)
        );
    }
}
