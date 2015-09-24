<?php
namespace DvsaEventApi;

use DvsaCommonApi\Transaction\ControllerTransactionAwareInitializer;
use DvsaCommonApi\Transaction\ServiceTransactionAwareInitializer;
use DvsaEventApi\Factory\Service\EventServiceFactory;
use DvsaEventApi\Service\EventService;
use Zend\EventManager\EventInterface;
use Zend\Http\Client as HttpClient;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Zend module containing the main factory for MOT API services
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ControllerProviderInterface,
    ServiceProviderInterface
{

    public static $em;

    public function getAutoloaderConfig()
    {
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories'  => [
                EventService::class => EventServiceFactory::class,
            ],
            'invokables' => [
            ],
            'initializers' => [
                'transactionAware' => ServiceTransactionAwareInitializer::class,
            ]
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to seed
     * such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerConfig()
    {
        return [
            'initializers' => [
                'transactionAware' => ControllerTransactionAwareInitializer::class,
            ]
        ];
    }
}
