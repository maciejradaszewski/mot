<?php

namespace Site\Factory\Controller;

use DvsaClient\MapperFactory;
use Site\Controller\SiteSearchController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SiteSearchControllerFactory
 * @package Site\Factory\Controller
 */
class SiteSearchControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     * @return SiteSearchController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $appServiceLocator = $controllerManager->getServiceLocator();

        /** @var MapperFactory */
        $mapper = $appServiceLocator->get(MapperFactory::class);

        return new SiteSearchController($mapper);
    }
}