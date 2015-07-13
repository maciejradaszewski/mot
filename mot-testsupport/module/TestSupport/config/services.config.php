<?php


use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\Obfuscate\ParamEncrypter;
use DvsaCommon\Obfuscate\ParamObfuscator;

use Zend\ServiceManager\ServiceLocatorInterface;

use Doctrine\ORM\EntityManager;
use TestSupport\Service\AccountDataService;
use TestSupport\Service\AccountService;
use TestSupport\Service\SiteUserDataService;
use TestSupport\Service\VehicleService;
use TestSupport\Service\SlotTransactionService;
use TestSupport\Service\JsonErrorHandlingListener;
use TestSupport\Service\CSCOService;
use TestSupport\Service\AreaOffice1Service;
use TestSupport\Service\AreaOffice2Service;
use TestSupport\Service\FinanceUserService;
use TestSupport\Service\VtsService;
use TestSupport\Service\AEService;
use TestSupport\Service\TesterService;
use TestSupport\Service\PasswordResetService;
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
                return new Client(new \Zend\Http\Client(), $config['apiUrl']);
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
        AreaOffice1Service::class          => \TestSupport\Factory\AreaOffice1ServiceFactory::class,
        AreaOffice2Service::class          => \TestSupport\Factory\AreaOffice2ServiceFactory::class,
        FinanceUserService::class          => \TestSupport\Factory\FinanceUserServiceFactory::class,
        VtsService::class                  => \TestSupport\Factory\VtsServiceFactory::class,
        TestSupportRestClientHelper::class => \TestSupport\Factory\TestSupportRestClientFactory::class,
        NotificationsHelper::class         => \TestSupport\Factory\NotificationsHelperFactory::class,
        SitePermissionsHelper::class       => \TestSupport\Factory\SitePermissionsHelperFactory::class,
        AEService::class                   => \TestSupport\Factory\AEServiceFactory::class,
        TesterService::class               => \TestSupport\Factory\TesterServiceFactory::class,
        InactiveTesterService::class      =>  \TestSupport\Factory\InactiveTesterServiceFactory::class,
        PasswordResetService::class        => \TestSupport\Factory\PasswordResetServiceFactory::class,
        VehicleExaminerService::class      => \TestSupport\Factory\VehicleExaminerServiceFactory::class,
        VM10519UserService::class          => \TestSupport\Factory\VM10519UserServiceFactory::class,
        TesterAuthorisationStatusService::class => TesterAuthorisationStatusServiceFactory::class,
    ]
];