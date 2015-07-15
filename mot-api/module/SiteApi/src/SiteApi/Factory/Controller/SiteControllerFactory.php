<?php

namespace SiteApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use SiteApi\Controller\SiteController;
use SiteApi\Service\SiteService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SiteControllerFactory
 * @package SiteApi\Factory\Controller
 */
class SiteControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     * @return SiteController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new SiteController(
            $serviceLocator->get(SiteService::class)
        );
    }
}
