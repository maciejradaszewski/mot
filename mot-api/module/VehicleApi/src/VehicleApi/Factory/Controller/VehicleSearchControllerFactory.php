<?php

namespace VehicleApi\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use VehicleApi\Controller\VehicleSearchController;
use DvsaElasticSearch\Service\ElasticSearchService as SearchService;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;

/**
 * Create instance of service VehicleSearchService
 *
 * @package DvsaMotApi\Factory\Service
 */
class VehicleSearchControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {

        $sl = $controllerManager->getServiceLocator();

        $vehicleSearchService = $sl->get('ElasticSearchService');
        $vehicleSearchParam = $sl->get(VehicleSearchParam::class);

        $controller = new VehicleSearchController($vehicleSearchService, $vehicleSearchParam);

        return $controller;

    }
}
