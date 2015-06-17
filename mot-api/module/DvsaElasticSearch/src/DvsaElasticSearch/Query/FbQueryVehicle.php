<?php

namespace DvsaElasticSearch\Query;

use DvsaCommon\Date\DateUtils;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Repository\VehicleRepository;

/**
 * Class FbQueryVehicle
 *
 * I answer as a fallback for all Vehicle that match the set search criteria.
 *
 * @package DvsaElasticSearch\Query
 */
class FbQueryVehicle implements IQuery
{
    const SEARCH_TYPE_VIN = 'vin';

    /* @var VehicleRepository */
    protected $vehicleRepo;

    /**
     * @param VehicleSearchParam $searchParams
     * @param $esConn
     * @param $esConfig
     *
     * @return array
     */
    public function execute($searchParams, $esConn = null, $esConfig = null)
    {
        /* @var VehicleRepository */
        $this->vehicleRepo = $searchParams->getRepository(Vehicle::class);
        $vehicles = $this->vehicleRepo->search(
            $searchParams->getVin(),
            $searchParams->getRegistration(),
            $searchParams->getSearchType() == self::SEARCH_TYPE_VIN ? true : false
        );

        $search['resultCount'] = count($vehicles);
        $search['totalResultCount'] = count($vehicles);
        $search['data'] = $this->extractVehicles($vehicles);
        $search['searched'] = ['isElasticSearch' => false] + $searchParams->toArray();

        return $search;
    }

    /**
     * @param Vehicle[] $vehicles
     * @return array
     */
    public function extractVehicles($vehicles)
    {
        $results = [];
        foreach ($vehicles as $vehicle) {
            $results[$vehicle->getId()] = [
                'vin'           => $vehicle->getVin(),
                'registration'  => $vehicle->getRegistration(),
                'make'          => $vehicle->getMakeName(),
                'model'         => $vehicle->getModelName(),
                'displayDate'   => $vehicle->getLastUpdatedOn() !== null ?
                    $vehicle->getLastUpdatedOn()->format('d M Y') :
                    null,
            ];
        }
        return $results;
    }
}