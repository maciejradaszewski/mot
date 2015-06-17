<?php

namespace DvsaCommon\Auth\Assertion;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Exception\UnauthorisedException;

class AcknowledgeSpecialNoticeAssertion
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
     * @param array $authorisationForMotTesting
     * @return bool
     */
    public function isGranted(array $authorisationForMotTesting)
    {
        try {
            $this->assertGranted($authorisationForMotTesting);
        } catch (UnauthorisedException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param array $authorisationForMotTesting
     * @throws UnauthorisedException
     */
    public function assertGranted(array $authorisationForMotTesting)
    {
        $hasAcknowledgePermission = $this->authorisationService->isGranted(PermissionInSystem::SPECIAL_NOTICE_ACKNOWLEDGE);
        if (!$hasAcknowledgePermission || !$this->hasAuthorisation($authorisationForMotTesting)) {
            throw new UnauthorisedException('Acknowledge special notice assertion failed.');
        }
    }

    /**
     * A person is has at least one authorisation in any status is considered to be a "tester"
     * @param array $authorisationForMotTesting
     * @return bool
     */
    private function hasAuthorisation(array $authorisationForMotTesting)
    {
        foreach ($authorisationForMotTesting as $status) {
            if (!is_null($status)) {
                return true;
            }
        }

        return false;
    }
}
