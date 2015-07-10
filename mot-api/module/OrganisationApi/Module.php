<?php

namespace OrganisationApi;

use DvsaCommon\Utility\Hydrator;
use DvsaCommonApi\Service\AddressService;
use DvsaCommonApi\Service\ContactDetailsService;
use OrganisationApi\Model\NominationVerifier;
use OrganisationApi\Model\Operation\DirectNominationOperation;
use OrganisationApi\Model\Operation\NominateByRequestOperation;
use OrganisationApi\Model\RoleAvailability;
use OrganisationApi\Service\AuthorisedExaminerPrincipalService;
use OrganisationApi\Service\AuthorisedExaminerSearchService;
use OrganisationApi\Service\AuthorisedExaminerService;
use OrganisationApi\Service\AuthorisedExaminerSlotService;
use OrganisationApi\Service\MotTestLogService;
use OrganisationApi\Service\NominateRoleService;
use OrganisationApi\Service\OrganisationNominationService;
use OrganisationApi\Service\OrganisationPositionService;
use OrganisationApi\Service\OrganisationRoleService;
use OrganisationApi\Service\OrganisationService;
use OrganisationApi\Service\OrganisationSlotUsageService;
use OrganisationApi\Service\SiteService;

/**
 * Class Module
 *
 * @package OrganisationApi
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
            'factories' => [
                Hydrator::class                           => \OrganisationApi\Factory\HydratorFactory::class,
                OrganisationService::class                => \OrganisationApi\Factory\Service\OrganisationServiceFactory::class,
                AddressService::class                     => \OrganisationApi\Factory\Service\AddressServiceFactory::class,
                ContactDetailsService::class              => \OrganisationApi\Factory\Service\ContactDetailsServiceFactory::class,
                AuthorisedExaminerService::class          => \OrganisationApi\Factory\Service\AuthorisedExaminerServiceFactory::class,
                AuthorisedExaminerSearchService::class    => \OrganisationApi\Factory\Service\AuthorisedExaminerSearchServiceFactory::class,
                AuthorisedExaminerPrincipalService::class => \OrganisationApi\Factory\Service\AuthorisedExaminerPrincipalServiceFactory::class,
                AuthorisedExaminerSlotService::class      => \OrganisationApi\Factory\Service\AuthorisedExaminerSlotServiceFactory::class,
                OrganisationPositionService::class        => \OrganisationApi\Factory\Service\OrganisationPositionServiceFactory::class,
                OrganisationSlotUsageService::class       => \OrganisationApi\Factory\Service\OrganisationSlotUsageServiceFactory::class,
                OrganisationRoleService::class            => \OrganisationApi\Factory\Service\OrganisationRoleServiceFactory::class,
                RoleAvailability::class                   => \OrganisationApi\Factory\Model\RoleAvailabilityFactory::class,
                NominateRoleService::class                => \OrganisationApi\Factory\Service\NominateRoleServiceFactory::class,
                NominateByRequestOperation::class         => \OrganisationApi\Factory\Service\NominateByRequestOperationFactory::class,
                DirectNominationOperation::class          => \OrganisationApi\Factory\Service\DirectNominationOperationFactory::class,
                OrganisationNominationService::class      => \OrganisationApi\Factory\Service\OrganisationNominationServiceFactory::class,
                NominationVerifier::class                 => \OrganisationApi\Factory\Service\NominateVerifierFactory::class,
                SiteService::class                        => \OrganisationApi\Factory\Service\SiteServiceFactory::class,
                MotTestLogService::class                  => \OrganisationApi\Factory\Service\MotTestLogServiceFactory::class,
            ],
        ];
    }
}
