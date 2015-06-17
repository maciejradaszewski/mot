<?php

namespace DvsaCommon\Auth\Assertion;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Exception\UnauthorisedException;

class RefuseToTestAssertion
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
        $this->authorisationService->assertGrantedAtSite(PermissionAtSite::MOT_TEST_REFUSE_TEST_AT_SITE, $siteId);
        $this->authorisationService->assertGranted(PermissionInSystem::MOT_TEST_REFUSE_TEST);
    }
}
