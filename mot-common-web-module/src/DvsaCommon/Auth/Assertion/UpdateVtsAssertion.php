<?php

namespace DvsaCommon\Auth\Assertion;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Exception\UnauthorisedException;

class UpdateVtsAssertion
{
    /**
     * @var MotAuthorisationServiceInterface
     */
    private $authorisationService;

    /**
     * @param MotAuthorisationServiceInterface $authorisationService
     */
    public function __construct(MotAuthorisationServiceInterface $authorisationService)
    {
        $this->authorisationService = $authorisationService;
    }

    public function assertUpdateAddress($siteId)
    {
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::VTS_UPDATE_ADDRESS, $siteId);
    }

    public function assertUpdateCountry($siteId)
    {
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::VTS_UPDATE_COUNTRY, $siteId);
    }

    public function assertUpdateEmail($siteId)
    {
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::VTS_UPDATE_EMAIL, $siteId);
    }

    public function assertUpdatePhone($siteId)
    {
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::VTS_UPDATE_PHONE, $siteId);
    }

    /**
     * @param string $permission
     * @param int $siteId
     * @return bool
     */
    private function isGrantedAtSite($permission, $siteId)
    {
        try {
            $this->authorisationService->assertGrantedAtSite($permission, $siteId);
        } catch (UnauthorisedException $exception) {
            return false;
        }

        return true;
    }

    public function assertUpdateTestingFacilities($siteId)
    {
        return $this->authorisationService->assertGrantedAtSite(PermissionAtSite::VTS_UPDATE_TESTING_FACILITIES_DETAILS,$siteId);
    }
}
