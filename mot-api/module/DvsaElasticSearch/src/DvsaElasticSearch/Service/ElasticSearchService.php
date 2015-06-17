<?php

namespace DvsaElasticSearch\Service;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaElasticSearch\Query\FbQueryMotTest;
use DvsaElasticSearch\Query\FbQueryMotTestLog;
use DvsaElasticSearch\Query\FbQuerySite;
use DvsaElasticSearch\Query\FbQueryVehicle;
use DvsaElasticSearch\Query\SuperSearchQuery;
use DvsaEntities\DqlBuilder\SearchParam\MotTestSearchParam;
use DvsaEntities\DqlBuilder\SearchParam\VehicleTestingStationSearchParam;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;
use Zend\Di\ServiceLocatorInterface;
use Zend\Http\Request;

/**
 * Class ElasticSearchService
 *
 * This class is responsible for handling all Elasatic Search based "super search" queries
 * from the main API.
 *
 * Currently it supports:
 *
 *    - MOT Tests by
 *    - Vehicles by
 *
 * @package DvsaElasticSearch\Service
 */
class ElasticSearchService
{
    /** @var AuthorisationServiceInterface */
    protected $authService;


    /**
     * This creates the ES search service. It requires the following services:
     *
     * @param AuthorisationServiceInterface                 $authService
     * @internal param $
     */
    public function __construct(
        AuthorisationServiceInterface $authService
    ) {
        $this->authService = $authService;
    }

    /**
     * Search for MOT tests.
     *
     * @param MotTestSearchParam $params
     *
     * @return array
     * @throws \UnexpectedValueException
     */
    public function findTests(MotTestSearchParam $params)
    {
        $this->checkPermissions(PermissionInSystem::MOT_TEST_LIST);

        return SuperSearchQuery::execute($params, new FbQueryMotTest());
    }


    /**
     * Search for MOT tests Log.
     *
     * @param MotTestSearchParam $params
     *
     * @return array
     * @throws \UnexpectedValueException
     */
    public function findTestsLog(MotTestSearchParam $params)
    {
        $this->authService->assertGrantedAtOrganisation(
            PermissionAtOrganisation::MOT_TEST_LIST, $params->getOrganisationId()
        );

        return SuperSearchQuery::execute($params, new FbQueryMotTestLog());
    }

    /**
     * Search for Vehicles
     *
     * @param VehicleSearchParam $params
     *
     * @return array
     * @throws \UnexpectedValueException
     */
    public function findVehicles(VehicleSearchParam $params)
    {
        $this->authService->assertGranted(PermissionInSystem::VEHICLE_READ);
        return SuperSearchQuery::execute($params, new FbQueryVehicle());
    }

    /**
     * Provides the ability to check the users access to the current search
     *
     * @param string $permission
     */
    protected function checkPermissions($permission)
    {
        $this->authService->assertGranted($permission);
    }
}
