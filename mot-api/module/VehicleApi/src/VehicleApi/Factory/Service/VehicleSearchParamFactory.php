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

        // @todo - get the SearchParamHelper from the SM and inject it into VehicleSearchParam.
        // Make sure this change doesn't affect potential multiple calls of VehicleSearchParam::__construct()

        $request = $sl->get('Request');
        $em = $sl->get('Doctrine\ORM\EntityManager');

        $vehicleSearchParamsHelper = new VehicleSearchParamsHelper($request);

        $searchParams = new VehicleSearchParam(
            $em,
            $vehicleSearchParamsHelper->getSearchParam(),
            $vehicleSearchParamsHelper->getSearchTypeParam()
        );

        $searchParams->loadStandardDataTableValuesFromRequest($request);
        $searchParams->process();

        return $searchParams;

    }
}
