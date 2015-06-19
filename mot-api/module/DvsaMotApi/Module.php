<?php

namespace DvsaMotApi;

use Core\Authorisation\Assertion\WebPerformMotTestAssertion;
use Core\Factory\WebPerformMotTestAssertionFactory;
use Doctrine\Tests\ORM\Mapping\User;
use DvsaCommonApi\Transaction\ControllerTransactionAwareInitializer;
use DvsaCommonApi\Transaction\ServiceTransactionAwareInitializer;
use DvsaEntities\Repository\TestItemCategoryRepository;
use DvsaMotApi\Factory\Repository\DvlaVehicleRepositoryFactory;
use DvsaMotApi\Factory\Service\MotTestOptionsServiceFactory;
use DvsaMotApi\Factory\Service\VehicleHistoryServiceFactory;
use DvsaMotApi\Factory\TestItemCategoryRepositoryFactory;
use DvsaMotApi\Service\CertificateCreationService;
use DvsaMotApi\Service\MotTestDateHelper;
use DvsaMotApi\Service\MotTestOptionsService;
use DvsaMotApi\Service\MotTestReasonForRejectionService;
use DvsaMotApi\Service\MotTestStatusChangeNotificationService;
use DvsaMotApi\Service\TestingOutsideOpeningHoursNotificationService;
use DvsaMotApi\Service\UserService;
use DvsaMotApi\Service\Validator\BrakeTestConfigurationValidator;
use DvsaMotApi\Service\Validator\BrakeTestResultValidator;
use DvsaMotApi\Service\Validator\MotTestStatusChangeValidator;
use DvsaMotApi\Service\VehicleHistoryService;
use DvsaMotApi\Service\EmergencyServiceFactory;
use DvsaMotApi\Service\EmergencyService;
use DvsaMotApi\Validator\UsernameValidator;
use Zend\EventManager\EventInterface;
use Zend\Http\Client as HttpClient;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use DvsaMotApi\Factory\Validator\UsernameValidatorFactory;
use DvsaMotApi\Service\Validator\ReplacementCertificateDraftChangeValidator;
use DvsaMotApi\Factory\Service\Validator\ReplacementCertificateDraftChangeValidatorFactory;
use DvsaMotApi\Service\CreateMotTestService;

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
        return [
            \Zend\Loader\ClassMapAutoloader::class => [
                __DIR__ . '/autoload_classmap.php',
            ],
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'DvsaRbac' => __DIR__ . '/src/DvsaRbac',
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories'  => [
                UserService::class => \DvsaMotApi\Factory\Service\UserServiceFactory::class,
                'VehicleService' => \DvsaMotApi\Factory\Service\VehicleServiceFactory::class,
                EmergencyService::class => \DvsaMotApi\Factory\Service\EmergencyServiceFactory::class,
                'EnforcementMotTestResultService' => \DvsaMotApi\Factory\Service\EnforcementMotTestResultServiceFactory::class,
                'EnforcementSiteAssessmentService' => \DvsaMotApi\Factory\Service\EnforcementSiteAssessmentServiceFactory::class,
                'TestItemSelectorService' => \DvsaMotApi\Factory\Service\TestItemSelectorServiceFactory::class,
                'TesterService' => \DvsaMotApi\Factory\Service\TesterServiceFactory::class,
                'TesterSearchService' => \DvsaMotApi\Factory\Service\TesterSearchServiceFactory::class,
                'TesterExpiryService' => \DvsaMotApi\Factory\Service\TesterExpiryServiceFactory::class,
                'BrakeTestResultService' => \DvsaMotApi\Factory\Service\BrakeTestResultServiceFactory::class,
                'MotTestSecurityService' => \DvsaMotApi\Factory\Service\MotTestSecurityServiceFactory::class,
                'MotTestService' => \DvsaMotApi\Factory\Service\MotTestServiceFactory::class,
                CreateMotTestService::class => \DvsaMotApi\Factory\Service\CreateMotTestServiceFactory::class,
                'MotTestShortSummaryService' => \DvsaMotApi\Factory\Service\MotTestShortSummaryServiceFactory::class,
                'MotTestStatusService' => \DvsaMotApi\Factory\Service\MotTestStatusServiceFactory::class,
                'MotTestStatusChangeService' => \DvsaMotApi\Factory\Service\MotTestStatusChangeServiceFactory::class,
                MotTestStatusChangeNotificationService::class => \DvsaMotApi\Factory\Service\MotTestStatusChangeNotificationFactory::class,
                TestingOutsideOpeningHoursNotificationService::class => \DvsaMotApi\Factory\Service\TestingOutsideOpeningHoursNotificationServiceFactory::class,
                MotTestDateHelper::class => \DvsaMotApi\Factory\Service\MotTestDateHelperFactory::class,
                'OtpService' => \DvsaMotApi\Factory\Service\OtpServiceFactory::class,
                'TestSlotTransactionService' => \DvsaMotApi\Factory\Service\TestSlotTransactionServiceFactory::class,
                'MotTestTypeService' => \DvsaMotApi\Factory\Service\MotTestTypeServiceFactory::class,
                'MotTestCompareService' => \DvsaMotApi\Factory\Service\MotTestCompareServiceFactory::class,
                'MotTestValidator' => \DvsaMotApi\Factory\Service\Validator\MotTestValidatorFactory::class,
                MotTestStatusChangeValidator::class => \DvsaMotApi\Factory\Service\Validator\MotTestChangeValidatorFactory::class,
                'MotTestRepository' => \DvsaMotApi\Factory\MotTestRepositoryFactory::class,
                'MotTestTypeRepository' => \DvsaMotApi\Factory\MotTestTypeRepositoryFactory::class,
                'RetestEligibilityValidator' => \DvsaMotApi\Factory\Service\Validator\RetestEligibilityValidatorFactory::class,
                'DvlaVehicleRepository' => DvlaVehicleRepositoryFactory::class,
                'VehicleRepository' => \DvsaMotApi\Factory\VehicleRepositoryFactory::class,
                'CertificateExpiryService' => \DvsaMotApi\Factory\Service\CertificateExpiryServiceFactory::class,
                'ConfigurationRepository' => \DvsaMotApi\Factory\ConfigurationRepositoryFactory::class,
                'RfrRepository' => \DvsaMotApi\Factory\RfrRepositoryFactory::class,
                'OdometerReadingDeltaAnomalyChecker' => \DvsaMotApi\Factory\Service\Validator\OdometerReadingDeltaAnomalyCheckerFactory::class,
                'OdometerReadingRepository' => \DvsaMotApi\Factory\OdometerReadingRepositoryFactory::class,
                'OdometerReadingUpdatingService' => \DvsaMotApi\Factory\Service\OdometerReadingUpdatingServiceFactory::class,
                'OdometerReadingQueryService' => \DvsaMotApi\Factory\Service\OdometerReadingQueryServiceFactory::class,
                'RoleRefreshService' => \DvsaMotApi\Factory\Service\RoleRefreshServiceFactory::class,
                'MotTestMapper' => \DvsaMotApi\Factory\Service\Mapper\MotTestMapperFactory::class,
                //  @ARCHIVE VM-4532    MotDemoTestService
                CertificateCreationService::class => \DvsaMotApi\Factory\Service\CertificateCreationServiceFactory::class,
                'CertificateReplacementRepository' => \DvsaMotApi\Factory\CertificateReplacementRepositoryFactory::class,
                'ReplacementCertificateUpdater' => \DvsaMotApi\Factory\Service\ReplacementCertificateUpdaterFactory::class,
                'ReplacementCertificateDraftRepository' => \DvsaMotApi\Factory\ReplacementCertificateDraftRepositoryFactory::class,
                'ReplacementCertificateDraftCreator' => \DvsaMotApi\Factory\Service\ReplacementCertificateDraftCreatorFactory::class,
                'ReplacementCertificateDraftUpdater' => \DvsaMotApi\Factory\Service\ReplacementCertificateDraftUpdaterFactory::class,
                'ReplacementCertificateService' => \DvsaMotApi\Factory\Service\ReplacementCertificateServiceFactory::class,
                'CertificateChangeService' => \DvsaMotApi\Factory\Service\CertificateChangeServiceFactory::class,
                MotTestReasonForRejectionService::class =>
                    \DvsaMotApi\Factory\Service\MotTestReasonForRejectionServiceFactory::class,
                TestItemCategoryRepository::class => TestItemCategoryRepositoryFactory::class,
                VehicleHistoryService::class => VehicleHistoryServiceFactory::class,
                MotTestOptionsService::class => MotTestOptionsServiceFactory::class,
                UsernameValidator::class => UsernameValidatorFactory::class,
                ReplacementCertificateDraftChangeValidator::class   => ReplacementCertificateDraftChangeValidatorFactory::class,
            ],
            'invokables' => [
                'BrakeTestConfigurationValidator' => BrakeTestConfigurationValidator::class,
                'BrakeTestResultValidator' => BrakeTestResultValidator::class,
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
