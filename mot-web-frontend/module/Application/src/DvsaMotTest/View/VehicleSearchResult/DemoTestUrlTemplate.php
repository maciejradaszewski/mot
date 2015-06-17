<?php

namespace DvsaMotTest\View\VehicleSearchResult;

use DvsaMotTest\Constants\VehicleSearchSource;
use DvsaMotTest\Controller\StartTestConfirmationController;
use Zend\Mvc\Controller\Plugin\Url;
use DvsaMotTest\Model\VehicleSearchResult;

class DemoTestUrlTemplate implements VehicleSearchResultUrlTemplateInterface
{
    private $noRegistration;
    private $urlHelper;

    public function __construct($noRegistration, Url $urlPlugin)
    {
        $this->noRegistration = $noRegistration;
        $this->urlHelper = $urlPlugin;
    }

    public function getUrl(array $vehicle)
    {
        $vehicleId = $vehicle['id'];
        $vin = $vehicle['vin'];
        $registration = $vehicle['registration'];
        $helper = $this->urlHelper;
        $searchVrm = $vehicle['searchVrm'];
        $searchVin = $vehicle['searchVin'];

        return $helper->fromRoute(
            'start-demo-confirmation',
            [
                StartTestConfirmationController::ROUTE_PARAM_ID     => $vehicleId,
                StartTestConfirmationController::ROUTE_PARAM_NO_REG => $this->noRegistration,
            ],
            ['query' => ["vin"          => $vin,
                         'registration' => $registration,
                         'searchVrm'    => $searchVrm,
                         'searchVin'    => $searchVin]]
        );
    }

    /**
     * @param VehicleSearchResult $vehicle
     * @param array $searchParams
     * @return string
     */
    public function getStartMotTestUrl(VehicleSearchResult $vehicle, array $searchParams)
    {
        $vehicleId = $vehicle->getId();
        $vin = $vehicle->getVin();
        $registration = $vehicle->getRegistrationNumber();

        $helper = $this->urlHelper;
        $searchVrm = $searchParams['searchVrm'];
        $searchVin = $searchParams['searchVin'];

        return $helper->fromRoute(
            'start-demo-confirmation',
            [
                StartTestConfirmationController::ROUTE_PARAM_ID     => $vehicleId,
                StartTestConfirmationController::ROUTE_PARAM_NO_REG => $this->noRegistration,
            ],
            ['query' => ["vin"          => $vin,
                'registration' => $registration,
                'searchVrm'    => $searchVrm,
                'searchVin'    => $searchVin]]
        );
    }
}

