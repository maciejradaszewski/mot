<?php

namespace NotificationApi;

use NotificationApi\Service\NotificationService;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use NotificationApi\Service\Helper\SiteNominationEventHelper;
use NotificationApi\Factory\Helper\SiteNominationEventHelperFactory;
use NotificationApi\Service\Helper\OrganisationNominationEventHelper;
use NotificationApi\Factory\Helper\OrganisationNominationEventHelperFactory;
use NotificationApi\Factory\Service\PositionRemovalNotificationServiceFactory;
use NotificationApi\Service\PositionRemovalNotificationService;

/**
 * Class Module
 *
 * @package NotificationApi
 */
class Module
{
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
                NotificationService::class => \NotificationApi\Factory\NotificationServiceFactory::class,
                SiteNominationEventHelper::class => SiteNominationEventHelperFactory::class,
                OrganisationNominationEventHelper::class => OrganisationNominationEventHelperFactory::class,
                PositionRemovalNotificationService::class => PositionRemovalNotificationServiceFactory::class
            ],
            'invokables' => [

            ]
        ];
    }
}
