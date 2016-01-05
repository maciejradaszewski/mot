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
     * @return bool
     */
    public function isGranted()
    {
        try {
            $this->assertGranted();
        } catch (UnauthorisedException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @throws UnauthorisedException
     */
    public function assertGranted()
    {
        $hasAcknowledgePermission = $this->authorisationService->isGranted(PermissionInSystem::SPECIAL_NOTICE_ACKNOWLEDGE);
        if (!$hasAcknowledgePermission ) {
            throw new UnauthorisedException('Acknowledge special notice assertion failed.');
        }
    }
}
