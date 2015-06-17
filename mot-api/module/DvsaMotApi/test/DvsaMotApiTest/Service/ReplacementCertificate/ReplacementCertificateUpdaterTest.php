<?php

namespace DvsaMotApiTest\Service\ReplacementCertificate;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentity;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Auth\PermissionInSystem;
use Api\Check\CheckMessage;
use Api\Check\CheckResult;
use DvsaCommonApi\Service\Exception\BadRequestException;
use DvsaCommonApi\Service\Exception\ForbiddenException;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Service\MotTestSecurityService;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateUpdater;
use DvsaMotApiTest\Factory\ReplacementCertificateObjectsFactory;
use PHPUnit_Framework_TestCase;

/**
 * Class ReplacementCertificateUpdaterTest
 */
class ReplacementCertificateUpdaterTest extends PHPUnit_Framework_TestCase
{

    private $authorizationService;
    private $motTestSecurityService;
    private $motIdentityService;

    public function setUp()
    {
        $this->authorizationService = XMock::of('\DvsaAuthorisation\Service\AuthorisationServiceInterface', ['isGranted']);
        $this->motIdentityService = XMock::of('Zend\Authentication\AuthenticationService', ['getIdentity']);
        $this->motTestSecurityService = XMock::of(MotTestSecurityService::class);
    }

    public function testCreateGivenDraftShouldUpdateCertificate()
    {
        $this->userAssignedToVts();
        $this->userHasId(2);
        $this->permissionsGranted(
            [PermissionInSystem::CERTIFICATE_REPLACEMENT, PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS]
        );
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft()
            ->setReplacementReason("Reason");

        $motTest = $this->createSut()->update($draft);

        $this->assertEquals($draft->getPrimaryColour()->getId(), $motTest->getPrimaryColour()->getId());
        $this->assertEquals($draft->getSecondaryColour()->getId(), $motTest->getSecondaryColour()->getId());
        $this->assertEquals($draft->getExpiryDate(), $motTest->getExpiryDate());
        $this->assertEquals(
            $draft->getOdometerReading()->getValue(),
            $motTest->getOdometerReading()->getValue()
        );
        $this->assertEquals(
            $draft->getOdometerReading()->getUnit(),
            $motTest->getOdometerReading()->getUnit()
        );
        $this->assertEquals(
            $draft->getOdometerReading()->getResultType(),
            $motTest->getOdometerReading()->getResultType()
        );
    }

    public function testCreateGivenDifferentMotTestVersionWhenApplyingShouldThrowForbiddenException()
    {
        $this->setExpectedException(ForbiddenException::class);
        $this->userAssignedToVts();
        $this->permissionsGranted(
            [PermissionInSystem::CERTIFICATE_REPLACEMENT, PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS]
        );
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();
        $draft->getMotTest();
        $draft->setMotTestVersion(2);
        $this->createSut()->update($draft);
    }

    public function testCreateGivenDifferentUserWhenApplyingShouldThrowForbiddenException()
    {
        $this->setExpectedException(ForbiddenException::class);
        $this->userHasId(4);
        $this->userAssignedToVts();
        $this->permissionsGranted(
            [PermissionInSystem::CERTIFICATE_REPLACEMENT, PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS]
        );
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();
        $this->createSut()->update($draft);
    }

    public function testCreateGivenPartialRightsAndChangeIsOutsideModificationPeriodShouldThrowBadRequestException()
    {
        $this->setExpectedException(BadRequestException::class);
        $this->userAssignedToVts();
        $this->outsideOdometerReadingModificationWindow();
        $this->permissionsGranted([PermissionInSystem::CERTIFICATE_REPLACEMENT]);
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();

        $this->createSut()->update($draft);
    }

    public function testCreateGivenNoReasonForReplacementShouldThrowForbiddenException()
    {
        $this->setExpectedException(ForbiddenException::class);
        $this->userAssignedToVts();
        $this->outsideOdometerReadingModificationWindow(false);
        $this->permissionsGranted(
            [PermissionInSystem::CERTIFICATE_REPLACEMENT, PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS]
        );
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();
        $draft->setReplacementReason(null);

        $this->createSut()->update($draft);
    }

    public function testCreateGivenDifferentTesterAndNoReasonForItShouldThrowForbiddenException()
    {
        $this->setExpectedException(ForbiddenException::class);
        $this->userAssignedToVts(false);
        $this->outsideOdometerReadingModificationWindow(false);
        $this->permissionsGranted([PermissionInSystem::CERTIFICATE_REPLACEMENT]);
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft();
        $draft->setReasonForDifferentTester(null);

        $this->createSut()->update($draft);
    }

    public function testIfMakeNameDefinedThenUnassociateMakeEntityAndSetMotTestMakeFreeText()
    {
        $this->userAssignedToVts();
        $this->userHasId(2);
        $this->permissionsGranted(
            [PermissionInSystem::CERTIFICATE_REPLACEMENT, PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS]
        );
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft()
            ->setReplacementReason("Reason")
            ->setMakeName('This is a make');

        $motTest = $this->createSut()->update($draft);

        $this->assertNull($motTest->getMake());
        $this->assertEquals('This is a make', $motTest->getMakeName());
    }

    public function testIfModelNameDefinedThenUnassociateModelEntityAndSetMotTestModelFreeText()
    {
        $this->userAssignedToVts();
        $this->userHasId(2);
        $this->permissionsGranted(
            [PermissionInSystem::CERTIFICATE_REPLACEMENT, PermissionInSystem::CERTIFICATE_REPLACEMENT_SPECIAL_FIELDS]
        );
        $draft = ReplacementCertificateObjectsFactory::replacementCertificateDraft()
            ->setReplacementReason("Reason")
            ->setModelName('This is a model');

        $motTest = $this->createSut()->update($draft);

        $this->assertNull($motTest->getModel());
        $this->assertEquals('This is a model', $motTest->getModelName());
    }

    private function createSut()
    {
        return new ReplacementCertificateUpdater(
            $this->motTestSecurityService,
            $this->authorizationService,
            $this->motIdentityService
        );
    }

    // TODO: move to a helper
    private function permissionsGranted($permissions)
    {
        $this->authorizationService->expects($this->any())
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

    private function outsideOdometerReadingModificationWindow($decision = true)
    {
        $this->motTestSecurityService->expects($this->any())
            ->method('validateOdometerReadingModificationWindowOpen')
            ->will($this->returnValue($decision ? CheckResult::with(CheckMessage::withError()) : CheckResult::ok()));
    }

    private function userHasId($id = null)
    {
        $this->motIdentityService->expects($this->any())->method("getIdentity")
            ->will($this->returnValue(new MotIdentity($id, null)));
    }
}
