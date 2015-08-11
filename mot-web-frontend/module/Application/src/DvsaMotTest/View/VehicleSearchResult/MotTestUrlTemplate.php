<?php

namespace DvsaMotTest\View\VehicleSearchResult;

use DvsaCommon\Enum\MotTestTypeCode;
use DvsaMotTest\Constants\VehicleSearchSource;
use DvsaMotTest\Controller\StartTestConfirmationController;
use DvsaMotTest\Model\VehicleSearchResult;
use Zend\Mvc\Controller\Plugin\Url;

class MotTestUrlTemplate implements VehicleSearchResultUrlTemplateInterface
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
        $sourceType = $vehicle['source'];
        $vin = $vehicle['vin'];
        $registration = $vehicle['registration'];
        $helper = $this->urlHelper;
        $searchVrm = $vehicle['searchVrm'];
        $searchVin = $vehicle['searchVin'];

        return $helper->fromRoute(
            'start-test-confirmation',
            [
                'controller'                                        => 'StartTestConfirmation',
                'action'                                            => 'index',
                StartTestConfirmationController::ROUTE_PARAM_ID     => $vehicleId,
                StartTestConfirmationController::ROUTE_PARAM_NO_REG => $this->noRegistration,
                StartTestConfirmationController::ROUTE_PARAM_SOURCE => $sourceType,
            ],
            ['query' => ["vin"          => $vin,
                         'registration' => $registration,
                         'searchVrm'    => $searchVrm,
                         'searchVin'    => $searchVin]]
        );
    }

    public function getStartMotTestUrl(VehicleSearchResult $vehicle, array $searchParams)
    {
        $vehicleId = $vehicle->getId();
        $sourceType = $vehicle->getSource();
        $vin = $vehicle->getVin();
        $registration = $vehicle->getRegistrationNumber();
        $helper = $this->urlHelper;
        $searchVrm = $searchParams['searchVrm'];
        $searchVin = $searchParams['searchVin'];

        if ($vehicle->isRetest()) {
            $retest = true;
        } else {
            $retest = false;
        }

        return $helper->fromRoute(
            'start-test-confirmation',
            [
                'controller'                                        => 'StartTestConfirmation',
                'action'                                            => 'index',
                StartTestConfirmationController::ROUTE_PARAM_ID     => $vehicleId,
                StartTestConfirmationController::ROUTE_PARAM_NO_REG => $this->noRegistration,
                StartTestConfirmationController::ROUTE_PARAM_SOURCE => $sourceType,
            ],
            ['query' => ["vin"          => $vin,
                'registration' => $registration,
                'searchVrm'    => $searchVrm,
                'searchVin'    => $searchVin,
                'retest'       => $retest]]
        );
    }
}

