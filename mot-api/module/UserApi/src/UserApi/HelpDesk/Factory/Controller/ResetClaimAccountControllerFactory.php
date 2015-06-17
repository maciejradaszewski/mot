<?php

namespace UserApi\HelpDesk\Factory\Controller;

use Doctrine\ORM\EntityManager;
use UserApi\HelpDesk\Controller\ResetClaimAccountController;
use UserApi\HelpDesk\Service\ResetClaimAccountService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ResetClaimAccountControllerFactory
 * @package UserApi\HelpDesk\Factory\Controller
 */
class ResetClaimAccountControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     * @return ResetClaimAccountController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new ResetClaimAccountController(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(ResetClaimAccountService::class)
        );
    }
}
