<?php

namespace AccountApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use AccountApi\Mapper\SecurityQuestionMapper;
use AccountApi\Service\SecurityQuestionService;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaEntities\Entity\SecurityQuestion;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SecurityQuestionServiceFactory
 * @package AccountApi\Factory
 */
class SecurityQuestionServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SecurityQuestionService(
            $serviceLocator->get(EntityManager::class)->getRepository(SecurityQuestion::class),
            new SecurityQuestionMapper(),
            $serviceLocator->get(ParamObfuscator::class)
        );
    }
}
