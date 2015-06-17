<?php

namespace SiteApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use SiteApi\Controller\SiteSearchController;
use SiteApi\Service\SiteSearchService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SiteSearchControllerFactory
 * @package SiteApi\Factory\Controller
 */
class SiteSearchControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     * @return SiteSearchController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new SiteSearchController(
            $serviceLocator->get(SiteSearchService::class)
        );
    }
}
