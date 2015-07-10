<?php
namespace DvsaEntities;

use DvsaCommonApi\Module\AbstractErrorHandlingModule;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Class Module
 */
class Module extends AbstractErrorHandlingModule implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
