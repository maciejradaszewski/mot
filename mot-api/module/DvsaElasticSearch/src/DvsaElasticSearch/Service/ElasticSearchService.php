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
use DvsaCommonApi\Model\SearchParam;
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

        $result = SuperSearchQuery::execute($params, new FbQueryMotTest());

        if($result->getResultCount() == 0 && $this->checkIfParamsNeedStripping($params)) {
            $result = SuperSearchQuery::execute($this->stripParams($params), new FbQueryMotTest());
        }
        return $result;
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
     * Search for MOT tests Log for current user
     *
     * @param MotTestSearchParam $params
     *
     * @return array
     * @throws \UnexpectedValueException
     */
    public function findTesterTestsLog(MotTestSearchParam $params)
    {
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

        $result = SuperSearchQuery::execute($params, new FbQueryVehicle());
        if($result['resultCount'] == 0 && $this->checkIfParamsNeedStripping($params)) {
            $result = SuperSearchQuery::execute($this->stripParams($params), new FbQueryVehicle());
        }
        return $result;
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

    /**
     * Strip vin and/or registration number if set to perform extra search
     * @param SearchParam $params
     *
     * @return VehicleSearchParam
     */
    protected function stripParams(SearchParam $params)
    {
        $strippedParams = clone($params);

        if($params->getVin() != NULL && strpos($params->getVin(), " ") !== FALSE) {
            $strippedParams->setVin(preg_replace('/\s+/', '', $params->getVin()));
        }

        if($params->getRegistration() != NULL && strpos($params->getRegistration(), " ") !== FALSE) {
            $strippedParams->setRegistration(preg_replace('/\s+/', '', $params->getRegistration()));
        }

        return $strippedParams;
    }

    /**
     * @param SearchParam $params
     * @return bool
     */
    protected function checkIfParamsNeedStripping(SearchParam $params)
    {
        if($params->getVin() != NULL && strpos($params->getVin(), " ") !== FALSE) {
            return true;
        }
        if($params->getRegistration() != NULL && strpos($params->getRegistration(), " ") !== FALSE) {
            return true;
        }
    }
}
