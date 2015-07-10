<?php

namespace SiteApi;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Database\Transaction;
use DvsaCommon\Utility\Hydrator;
use DvsaCommonApi\Filter\XssFilter;
use DvsaCommonApi\Service\ContactDetailsService;
use DvsaEntities\Entity\BrakeTestType;
use DvsaEntities\Entity\NonWorkingDayCountry;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use DvsaEntities\Entity\SiteContactType;
use DvsaEntities\Entity\SiteTestingDailySchedule;
use DvsaEntities\Entity\SiteType;
use DvsaMotApi\Service\Validator\BrakeTestConfigurationValidator;
use NotificationApi\Service\NotificationService;
use SiteApi\Factory\Model\NominationVerifierFactory;
use SiteApi\Factory\Service\MotTestInProgressServiceFactory;
use SiteApi\Factory\Service\SiteContactServiceFactory;
use SiteApi\Factory\Service\SiteSearchServiceFactory;
use SiteApi\Factory\Service\SiteSlotUsageServiceFactory;
use SiteApi\Factory\SitePersonnelFactory;
use SiteApi\Model\NominationVerifier;
use SiteApi\Model\Operation\NominateOperation;
use SiteApi\Model\RoleRestriction\SiteAdminRestriction;
use SiteApi\Model\RoleRestriction\SiteManagerRestriction;
use SiteApi\Model\RoleRestriction\TesterRestriction;
use SiteApi\Model\RoleRestrictionsSet;
use SiteApi\Service\DefaultBrakeTestsService;
use SiteApi\Service\EquipmentService;
use SiteApi\Service\Mapper\SiteBusinessRoleMapMapper;
use SiteApi\Service\Mapper\SiteBusinessRoleMapper;
use SiteApi\Service\MotTestInProgressService;
use SiteApi\Service\NominateRoleService;
use SiteApi\Service\SiteBusinessRoleService;
use SiteApi\Service\SiteContactService;
use SiteApi\Service\SiteNominationService;
use SiteApi\Service\SitePositionService;
use SiteApi\Service\SiteSearchService;
use SiteApi\Service\SiteService;
use SiteApi\Service\SiteSlotUsageService;
use SiteApi\Service\SiteTestingDailyScheduleService;
use SiteApi\Service\Validator\SiteTestingDailyScheduleValidator;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use DvsaEventApi\Service\EventService;

/**
 * Class Module
 *
 * @package SiteApi
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
                Hydrator::class                           =>
                    function (ServiceLocatorInterface $sm) {
                        return new Hydrator();
                    },
                SiteService::class                             =>
                    function (ServiceLocatorInterface $sm) {
                        $dvsaAuthorisationService = $sm->get('DvsaAuthorisationService');
                        $updateVtsAssertion = new UpdateVtsAssertion($dvsaAuthorisationService);

                        return new SiteService(
                            $sm->get(EntityManager::class),
                            $sm->get(EntityManager::class)->getRepository(SiteType::class),
                            $sm->get(EntityManager::class)->getRepository(Site::class),
                            $sm->get(EntityManager::class)->getRepository(SiteContactType::class),
                            $sm->get(EntityManager::class)->getRepository(BrakeTestType::class),
                            $sm->get(EntityManager::class)->getRepository(NonWorkingDayCountry::class),
                            $sm->get(Hydrator::class),
                            $sm->get('DvsaAuthorisationService'),
                            new SiteBusinessRoleMapMapper($sm->get(Hydrator::class)),
                            $sm->get(ContactDetailsService::class),
                            $sm->get(XssFilter::class),
                            $updateVtsAssertion
                        );
                    },
                SiteBusinessRoleService::class                 =>
                    function (ServiceLocatorInterface $sm) {
                        return new SiteBusinessRoleService(
                            new SiteBusinessRoleMapper(),
                            $sm->get(\Doctrine\ORM\EntityManager::class)
                                ->getRepository(\DvsaEntities\Entity\SiteBusinessRole::class),
                            $sm->get(\Doctrine\ORM\EntityManager::class)
                                ->getRepository(\DvsaEntities\Entity\SiteBusinessRoleMap::class),
                            $sm->get('DvsaAuthorisationService')
                        );
                    },
                SitePositionService::class                =>
                    function (ServiceManager $sm) {
                        $entityManager = $sm->get(EntityManager::class);
                        return new SitePositionService(
                            $sm->get(EventService::class),
                            $entityManager->getRepository(SiteBusinessRoleMap::class),
                            $sm->get('DvsaAuthorisationService'),
                            $entityManager,
                            $sm->get(NotificationService::class)
                        );
                    },
                NominateRoleService::class                =>
                    function (ServiceManager $sm) {
                        $em = $sm->get(EntityManager::class);

                        return new NominateRoleService(
                            $sm->get(\Doctrine\ORM\EntityManager::class),
                            $sm->get('DvsaAuthenticationService'),
                            $sm->get('DvsaAuthorisationService'),
                            $sm->get(NominateOperation::class),
                            new Transaction($em)
                        );
                    },
                EquipmentService::class                   =>
                    function (ServiceLocatorInterface $sm) {
                        return new EquipmentService(
                            $sm->get(EntityManager::class)->getRepository(Site::class),
                            $sm->get('DvsaAuthorisationService')
                        );
                    },
                NominateOperation::class                  =>
                    function (ServiceLocatorInterface $sm) {
                        return new NominateOperation(
                            $sm->get(EntityManager::class),
                            $sm->get(NominationVerifier::class),
                            $sm->get(SiteNominationService::class)
                        );
                    },
                NominationVerifier::class                 => NominationVerifierFactory::class,
                SiteNominationService::class              =>
                    function (ServiceLocatorInterface $sm) {
                        return new SiteNominationService(
                            $sm->get(NotificationService::class)
                        );
                    },
                SiteTestingDailyScheduleService::class    =>
                    function (ServiceLocatorInterface $sm) {
                        return new SiteTestingDailyScheduleService(
                            $sm->get(EntityManager::class)->getRepository(
                                SiteTestingDailySchedule::class
                            ),
                            $sm->get(EntityManager::class)->getRepository(Site::class),
                            new SiteTestingDailyScheduleValidator(),
                            $sm->get('DvsaAuthorisationService')
                        );
                    },
                DefaultBrakeTestsService::class           =>
                    function (ServiceLocatorInterface $sm) {
                        return new DefaultBrakeTestsService(
                            $sm->get(EntityManager::class)->getRepository(Site::class),
                            $sm->get(EntityManager::class)->getRepository(BrakeTestType::class),
                            new BrakeTestConfigurationValidator(),
                            $sm->get('DvsaAuthorisationService')
                        );
                    },
                MotTestInProgressService::class => MotTestInProgressServiceFactory::class,
                SiteSlotUsageService::class => SiteSlotUsageServiceFactory::class,
                SiteContactService::class => SiteContactServiceFactory::class,
                SiteSearchService::class => SiteSearchServiceFactory::class,
            ],
            'invokables' => [

            ]
        ];
    }
}
