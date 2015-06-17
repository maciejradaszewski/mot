<?php

namespace DvsaMotTest\View\VehicleSearchResult;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\UrlBuilder\VehicleUrlBuilderWeb;
use Zend\Mvc\Controller\Plugin\Url;
use DvsaMotTest\Model\VehicleSearchResult;

class CertificateUrlTemplate implements VehicleSearchResultUrlTemplateInterface
{
    private $urlHelper;
    private $authorisationService;

    public function __construct(MotAuthorisationServiceInterface $authorisationService, Url $urlPlugin)
    {
        $this->authorisationService = $authorisationService;
        $this->urlHelper = $urlPlugin;
    }

    public function getUrl(array $vehicle)
    {
        $vehicleId = $vehicle['id'];
        $vin = $vehicle['vin'];
        $registration = $vehicle['registration'];
        $isDvsaUser = $this->authorisationService->isGranted(PermissionInSystem::CERTIFICATE_READ_FROM_ANY_SITE);

        if ($isDvsaUser) {
            $url = VehicleUrlBuilderWeb::historyDvlaMotCertificates($vehicleId);
        } else {
            $url = VehicleUrlBuilderWeb::historyMotCertificates($vehicleId);
        }

        $query = http_build_query([ "vin" => $vin, 'registration' => $registration]);
        return $url . "?" . $query;
    }

    /**
     * @param VehicleSearchResult $vehicle
     * @return string
     */
    public function getStartMotTestUrl(VehicleSearchResult $vehicle)
    {
        $vehicleId = $vehicle->getId();
        $vin = $vehicle->getVin();
        $registration = $vehicle->getRegistrationNumber();

        $isDvsaUser = $this->authorisationService->isGranted(PermissionInSystem::CERTIFICATE_READ_FROM_ANY_SITE);

        if ($isDvsaUser) {
            $url = VehicleUrlBuilderWeb::historyDvlaMotCertificates($vehicleId);
        } else {
            $url = VehicleUrlBuilderWeb::historyMotCertificates($vehicleId);
        }

        $query = http_build_query([ "vin" => $vin, 'registration' => $registration]);
        return $url . "?" . $query;
    }
}

