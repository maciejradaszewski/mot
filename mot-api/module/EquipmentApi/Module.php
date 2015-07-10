<?php
namespace EquipmentApi;

use EquipmentApi\Service\EquipmentModelService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                EquipmentModelService::class => \EquipmentApi\Factory\Service\EquipmentModelServiceFactory::class,
            ],
        ];
    }
}
