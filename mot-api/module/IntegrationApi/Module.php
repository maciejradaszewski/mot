<?php

namespace IntegrationApi;

use IntegrationApi\DvlaVehicle\Service\DvlaVehicleUpdatedService;
use IntegrationApi\OpenInterface\Repository\OpenInterfaceMotTestRepository as OpenInterfaceMotTestRepository;
use IntegrationApi\OpenInterface\Service\OpenInterfaceMotTestService;
use IntegrationApi\TransportForLondon\Service\TransportForLondonMotTestService;
use IntegrationApi\DvlaInfo\Service\DvlaInfoMotHistoryService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;

/**
 * Class Module
 *
 * @package IntegrationApi
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            \Zend\Loader\ClassMapAutoloader::class => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                OpenInterfaceMotTestRepository::class   => \IntegrationApi\Factory\OpenInterfaceMotTestRepositoryFactory::class,
                OpenInterfaceMotTestService::class      => \IntegrationApi\Factory\OpenInterfaceMotTestServiceFactory::class,
                TransportForLondonMotTestService::class => \IntegrationApi\Factory\TransportForLondonMotTestServiceFactory::class,
                DvlaInfoMotHistoryService::class        => \IntegrationApi\Factory\DvlaInfoMotHistoryServiceFactory::class,
                DvlaVehicleUpdatedService::class        => \IntegrationApi\Factory\Service\DvlaVehicleUpdatedServiceFactory::class
            ],
        ];
    }
}
