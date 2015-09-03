<?php

namespace DvsaMotApi;

use Core\Authorisation\Assertion\WebPerformMotTestAssertion;
use Core\Factory\WebPerformMotTestAssertionFactory;
use Doctrine\Tests\ORM\Mapping\User;
use DvsaCommonApi\Transaction\ServiceTransactionAwareInitializer;
use DvsaEntities\Repository\TestItemCategoryRepository;
use DvsaMotApi\Factory\Repository\DvlaVehicleRepositoryFactory;
use DvsaMotApi\Factory\Service\MotTestOptionsServiceFactory;
use DvsaMotApi\Factory\Service\DemoTestAssessmentServiceFactory;
use DvsaMotApi\Factory\Service\VehicleHistoryServiceFactory;
use DvsaMotApi\Factory\Service\TesterMotTestLogServiceFactory;
use DvsaMotApi\Factory\TestItemCategoryRepositoryFactory;
use DvsaMotApi\Service\CertificateCreationService;
use DvsaMotApi\Service\MotTestDateHelperService;
use DvsaMotApi\Service\MotTestOptionsService;
use DvsaMotApi\Service\MotTestReasonForRejectionService;
use DvsaMotApi\Service\MotTestStatusChangeNotificationService;
use DvsaMotApi\Service\DemoTestAssessmentService;
use DvsaMotApi\Service\TestingOutsideOpeningHoursNotificationService;
use DvsaMotApi\Service\UserService;
use DvsaMotApi\Service\Validator\BrakeTestConfigurationValidator;
use DvsaMotApi\Service\Validator\BrakeTestResultValidator;
use DvsaMotApi\Service\Validator\MotTestStatusChangeValidator;
use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;
use DvsaMotApi\Service\VehicleHistoryService;
use DvsaMotApi\Service\EmergencyServiceFactory;
use DvsaMotApi\Service\EmergencyService;
use DvsaMotApi\Service\TesterMotTestLogService;
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
use DvsaMotApi\Helper\TesterQualificationStatusChangeEventHelper;
use DvsaMotApi\Factory\Helper\TesterQualificationStatusChangeEventHelperFactory;
use DvsaMotApi\Helper\RoleEventHelper;
use DvsaMotApi\Factory\Helper\RoleEventHelperFactory;
use DvsaMotApi\Helper\RoleNotificationHelper;
use DvsaMotApi\Factory\Helper\RoleNotificationHelperFactory;
use DvsaMotApi\Factory\Service\Validator\RetestEligibilityValidatorFactory;

/**
 * Zend module containing the main factory for MOT API services
 */
class Module implements
    ConfigProviderInterface,
    ServiceProviderInterface
{

    public static $em;

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
                MotTestDateHelperService::class => \DvsaMotApi\Factory\Service\MotTestDateHelperFactory::class,
                'TestSlotTransactionService' => \DvsaMotApi\Factory\Service\TestSlotTransactionServiceFactory::class,
                'MotTestTypeService' => \DvsaMotApi\Factory\Service\MotTestTypeServiceFactory::class,
                'MotTestCompareService' => \DvsaMotApi\Factory\Service\MotTestCompareServiceFactory::class,
                'MotTestValidator' => \DvsaMotApi\Factory\Service\Validator\MotTestValidatorFactory::class,
                MotTestStatusChangeValidator::class => \DvsaMotApi\Factory\Service\Validator\MotTestChangeValidatorFactory::class,
                'MotTestRepository' => \DvsaMotApi\Factory\MotTestRepositoryFactory::class,
                'MotTestTypeRepository' => \DvsaMotApi\Factory\MotTestTypeRepositoryFactory::class,
                RetestEligibilityValidator::class => RetestEligibilityValidatorFactory::class,
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
                DemoTestAssessmentService::class => DemoTestAssessmentServiceFactory::class,
                TesterQualificationStatusChangeEventHelper::class => TesterQualificationStatusChangeEventHelperFactory::class,
                TesterMotTestLogService::class => TesterMotTestLogServiceFactory::class,
                RoleEventHelper::class => RoleEventHelperFactory::class,
                RoleNotificationHelper::class => RoleNotificationHelperFactory::class
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
}
