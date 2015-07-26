<?php

namespace VehicleApi\Factory\Service;

use Doctrine\ORM\EntityManager;

use DvsaEntities\Entity\DvlaVehicleImportChanges;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;
use VehicleApi\Helper\VehicleSearchParams as VehicleSearchParamsHelper;

/**
 * Create instance of service VehicleSearchService
 *
 * @package DvsaMotApi\Factory\Service
 */
class VehicleSearchParamFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $request = $sl->get('Request');
        $vehicleSearchParamsHelper = new VehicleSearchParamsHelper($request);

        $searchParams = new VehicleSearchParam(
            $vehicleSearchParamsHelper->getSearchParam(),
            $vehicleSearchParamsHelper->getSearchTypeParam()
        );

        $searchParams->loadStandardDataTableValuesFromRequest($request);

        return $searchParams;
    }
}
