<?php

namespace DvsaCommon\Auth\Assertion;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
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

    /**
     * @param int $siteId
     *
     * @return bool
     */
    public function isGranted($siteId)
    {
        try {
            $this->assertGranted($siteId);
        } catch (UnauthorisedException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param int $siteId
     *
     * @throws UnauthorisedException
     */
    public function assertGranted($siteId)
    {
        $canUpdateName = $this->canUpdateName($siteId);
        $canUpdateCorrespondenceDetails = $this->canUpdateCorrespondenceDetails($siteId);
        $canUpdateBusinessDetailsAssertion = $this->canUpdateBusinessDetails($siteId);

        if (!in_array(true, [$canUpdateName, $canUpdateCorrespondenceDetails, $canUpdateBusinessDetailsAssertion])) {
            throw new UnauthorisedException("Update vts assertion failed");
        }
    }

    /**
     * @param int $siteId
     * @return bool
     */
    public function canUpdateName($siteId)
    {
        return $this->isGrantedAtSite(PermissionAtSite::VTS_UPDATE_NAME, $siteId);
    }

    /**
     * @param int $siteId
     * @return bool
     */
    public function canUpdateCorrespondenceDetails($siteId)
    {
        return $this->isGrantedAtSite(PermissionAtSite::VTS_UPDATE_CORRESPONDENCE_DETAILS, $siteId);
    }

    public function assertUpdateCorrespondenceDetails($siteId)
    {
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::VTS_UPDATE_CORRESPONDENCE_DETAILS, $siteId);
    }

    public function assertUpdateBusinessDetails($siteId)
    {
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS, $siteId);
    }

    /**
     * @param int $siteId
     * @return bool
     */
    public function canUpdateBusinessDetails($siteId)
    {
        return $this->isGrantedAtSite(PermissionAtSite::VTS_UPDATE_BUSINESS_DETAILS, $siteId);
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
}
