<?php

namespace UserAdmin\Presenter;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;

class UserProfileViewAuthorisation
{
    private $authorisationService;

    public function __construct(MotAuthorisationServiceInterface $authorisationService)
    {
        $this->authorisationService = $authorisationService;
    }

    public function canAssessDemoTest()
    {
        return $this->authorisationService->isGranted(PermissionInSystem::ASSESS_DEMO_TEST);
    }
}
