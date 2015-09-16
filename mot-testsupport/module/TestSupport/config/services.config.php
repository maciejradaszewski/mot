<?php

use DvsaCommon\HttpRestJson\ZendClient;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommon\Obfuscate\ParamObfuscator;
use TestSupport\Service\DVLAOperativeService;
use TestSupport\Service\VM10619RoleManagementUpgradeService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager;
use TestSupport\Service\AccountDataService;
use TestSupport\Service\AccountService;
use TestSupport\Service\SiteUserDataService;
use TestSupport\Service\VehicleService;
use TestSupport\Service\SlotTransactionService;
use TestSupport\Service\JsonErrorHandlingListener;
use TestSupport\Service\CSCOService;
use TestSupport\Service\CsmService;
use TestSupport\Service\DVLAManagerService;
use TestSupport\Service\AreaOffice1Service;
use TestSupport\Service\AreaOffice2Service;
use TestSupport\Service\CronUserService;
use TestSupport\Service\FinanceUserService;
use TestSupport\Service\VtsService;
use TestSupport\Service\AEService;
use TestSupport\Service\TesterService;
use TestSupport\Service\PasswordResetService;
use TestSupport\Service\SchemeManagerService;
use TestSupport\Service\SchemeUserService;
use TestSupport\Service\UserService;
use TestSupport\Service\VehicleExaminerService;
use TestSupport\Service\VM10519UserService;
use TestSupport\Service\DvlaVehicleService;
use TestSupport\Helper\TestSupportAccessTokenManager;
use TestSupport\Helper\TestSupportRestClientHelper;
use TestSupport\Helper\NotificationsHelper;
use TestSupport\Helper\SitePermissionsHelper;
use TestSupport\Service\InactiveTesterService;
use TestSupport\Service\TesterAuthorisationStatusService;
use TestSupport\Factory\TesterAuthorisationStatusServiceFactory;
use TestSupport\Service\AedmService;

return [
    'factories' => [
        TestSupportAccessTokenManager::class   => function (ServiceLocatorInterface $sm) {
            $config = $sm->get('config');
            return new TestSupportAccessTokenManager($config['apiUrl']);
        },
        JsonErrorHandlingListener::class       =>
            function (ServiceLocatorInterface $sm) {
                return new JsonErrorHandlingListener();
            },
        \DvsaCommon\HttpRestJson\Client::class =>
            function (ServiceLocatorInterface $sm) {
                $config = $sm->get('config');
                return new ZendClient(new \Zend\Http\Client(), $config['apiUrl']);
            },
        SiteUserDataService::class             =>
            function (ServiceLocatorInterface $sm) {
                return new SiteUserDataService(
                    $sm->get(NotificationsHelper::class),
                    $sm->get(SitePermissionsHelper::class)
                );
            },
        AccountDataService::class              => \TestSupport\Factory\AccountDataServiceFactory::class,
        AccountService::class                  =>
            function (ServiceLocatorInterface $sm) {
                $tokenManager = $sm->get(TestSupportAccessTokenManager::class);
                return new AccountService(
                    $sm->get(EntityManager::class),
                    $sm->get(Client::class),
                    $tokenManager
                );
            },
        VehicleService::class                  =>
            function (ServiceLocatorInterface $sm) {
                return new VehicleService($sm->get(EntityManager::class));
            },
        DvlaVehicleService::class                  =>
            function (ServiceLocatorInterface $sm) {
                return new DvlaVehicleService($sm->get(EntityManager::class));
            },
        SlotTransactionService::class          =>
            function (ServiceLocatorInterface $sm) {
                $tokenManager = $sm->get(TestSupportAccessTokenManager::class);
                return new SlotTransactionService($sm->get(Client::class), $tokenManager);
            },
        // @TODO after mot-common-web-module is part of composer remove the below lines as the module will
        // already have these services registered.
        ParamEncrypter::class              => \DvsaCommon\Obfuscate\Factory\ParamEncrypterFactory::class,
        ParamObfuscator::class             => \DvsaCommon\Obfuscate\Factory\ParamObfuscatorFactory::class,
        'ApplicationLog'                   => 'TestSupport\Factory\ApplicationLogFactory',
        CSCOService::class                 => \TestSupport\Factory\CSCOServiceFactory::class,
        CSMService::class                  => \TestSupport\Factory\CSMServiceFactory::class,
        DVLAManagerService::class          => \TestSupport\Factory\DVLAManagerServiceFactory::class,
        DVLAOperativeService::class          => \TestSupport\Factory\DVLAOperativeServiceFactory::class,
        AreaOffice1Service::class          => \TestSupport\Factory\AreaOffice1ServiceFactory::class,
        AreaOffice2Service::class          => \TestSupport\Factory\AreaOffice2ServiceFactory::class,
        CronUserService::class             => \TestSupport\Factory\CronUserServiceFactory::class,
        FinanceUserService::class          => \TestSupport\Factory\FinanceUserServiceFactory::class,
        UserService::class                 => \TestSupport\Factory\UserServiceFactory::class,
        VtsService::class                  => \TestSupport\Factory\VtsServiceFactory::class,
        TestSupportRestClientHelper::class => \TestSupport\Factory\TestSupportRestClientFactory::class,
        NotificationsHelper::class         => \TestSupport\Factory\NotificationsHelperFactory::class,
        SitePermissionsHelper::class       => \TestSupport\Factory\SitePermissionsHelperFactory::class,
        AEService::class                   => \TestSupport\Factory\AEServiceFactory::class,
        TesterService::class               => \TestSupport\Factory\TesterServiceFactory::class,
        SchemeManagerService::class        => \TestSupport\Factory\SchemeManagerServiceFactory::class,
        SchemeUserService::class           => \TestSupport\Factory\SchemeUserServiceFactory::class,
        InactiveTesterService::class       => \TestSupport\Factory\InactiveTesterServiceFactory::class,
        PasswordResetService::class        => \TestSupport\Factory\PasswordResetServiceFactory::class,
        VehicleExaminerService::class      => \TestSupport\Factory\VehicleExaminerServiceFactory::class,
        VM10519UserService::class          => \TestSupport\Factory\VM10519UserServiceFactory::class,
        VM10619RoleManagementUpgradeService::class          => \TestSupport\Factory\VM10619RoleManagementUpgradeServiceFactory::class,
        TesterAuthorisationStatusService::class => TesterAuthorisationStatusServiceFactory::class,
        AedmService::class                 => \TestSupport\Factory\AedmServiceFactory::class,
    ]
];