<?php

namespace DvsaMotTest\Factory\Controller;

use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaMotTest\Controller\VehicleSearchController;
use DvsaMotTest\Model\VehicleSearchResult;
use DvsaMotTest\Service\VehicleSearchService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaClient\MapperFactory;

/**
 * Create VehicleSearchController.
 */
class VehicleSearchControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return VehicleSearchController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();
        $vehicleSearchService = $serviceLocator->get(VehicleSearchService::class);
        $paramObfuscator = $serviceLocator->get(ParamObfuscator::class);
        $catalogService = $serviceLocator->get('CatalogService');
        $vehicleSearchModel = $serviceLocator->get(VehicleSearchResult::class);
        $mapperFactory = $serviceLocator->get(MapperFactory::class);

        return new VehicleSearchController(
            $vehicleSearchService, $paramObfuscator, $catalogService, $vehicleSearchModel, $mapperFactory
        );
    }
}
