<?php

namespace DvsaMotApiTest\Service\ReplacementCertificate;

use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommonApi\Service\Exception\ForbiddenException;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\CountryOfRegistration;
use DvsaEntities\Entity\MotTestStatus;
use DvsaMotApi\Service\MotTestSecurityService;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateDraftCreator;
use DvsaMotApiTest\Factory\MotTestObjectsFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class ReplacementCertificateDraftCreatorTest
 */
class ReplacementCertificateDraftCreatorTest extends PHPUnit_Framework_TestCase
{

    private $authService;
    private $motTestSecurityService;

    public function setUp()
    {
        $this->authService = XMock::of('DvsaAuthorisation\Service\AuthorisationServiceInterface', ['isGranted', 'getUserId']);
        $this->motTestSecurityService = XMock::of(MotTestSecurityService::class);
    }

    public function testCreateGivenMotTestNotBeingCertificateShouldThrowForbiddenException()
    {
        $this->setExpectedException(ForbiddenException::class);

        $motTest = MotTestObjectsFactory::motTest()->setStatus($this->createMotTestActiveStatus());
        $this->userAssignedToVts();
        $this->permissionsGranted(
            [PermissionInSystem::CERTIFICATE_REPLACEMENT, PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS]
        );

        $this->createSut()->create($motTest);
    }

    public function testCreateGivenNotAdminAndTesterNotAllowedToSeeMotTestShouldThrowForbiddenException()
    {
        $this->setExpectedException(ForbiddenException::class);

        $motTest = MotTestObjectsFactory::motTest();
        $this->userAssignedToVts(false);
        $this->permissionsGranted([PermissionInSystem::CERTIFICATE_REPLACEMENT]);

        $this->createSut()->create($motTest);
    }

    public function testCreateGivenMotTestShouldCreateValidDraft()
    {
        $motTest = MotTestObjectsFactory::motTest();
        $motTest->setCountryOfRegistration(new CountryOfRegistration());
        $this->userAssignedToVts();
        $this->permissionsGranted(
            [PermissionInSystem::CERTIFICATE_REPLACEMENT, PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS]
        );

        $draft = $this->createSut()->create($motTest);

        $this->assertEquals($motTest->getVersion(), $draft->getMotTestVersion());
        $this->assertEquals($motTest->getExpiryDate(), $draft->getExpiryDate());
        $this->assertEquals($motTest->getOdometerReading()->getValue(), $draft->getOdometerReading()->getValue());
        $this->assertEquals($motTest->getOdometerReading()->getUnit(), $draft->getOdometerReading()->getUnit());
        $this->assertEquals(
            $motTest->getOdometerReading()->getResultType(), $draft->getOdometerReading()->getResultType()
        );
    }

    private function createSut()
    {
        return new ReplacementCertificateDraftCreator(
            $this->motTestSecurityService,
            $this->authService
        );
    }

    // TODO: move to a helper
    private function permissionsGranted($permissions)
    {
        $this->authService->expects($this->any())
            ->method("isGranted")
            ->will(
                $this->returnCallback(
                    function ($arg) use (&$permissions) {
                        return in_array($arg, $permissions);
                    }
                )
            );
    }

    private function userAssignedToVts($decision = true)
    {
        $this->motTestSecurityService->expects($this->any())
            ->method('isCurrentTesterAssignedToMotTest')
            ->will($this->returnValue($decision));
    }

    private function createMotTestActiveStatus()
    {
        $status = XMock::of(MotTestStatus::class);
        $status
            ->expects($this->any())
            ->method("getName")
            ->willReturn(MotTestStatusName::ACTIVE);

        return $status;
    }
}
