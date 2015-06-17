<?php

namespace DvsaCommon\Auth\Assertion;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Exception\UnauthorisedException;

/**
 * Assertion(s) for creating user process
 */
class CreateUserAccountAssertion
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
     * @throws UnauthorisedException
     */
    public function assertGranted()
    {
        $this->authorisationService->assertGranted(PermissionInSystem::CREATE_USER_ACCOUNT);
    }
}
