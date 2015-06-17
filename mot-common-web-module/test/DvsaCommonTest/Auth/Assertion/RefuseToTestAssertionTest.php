<?php

namespace DvsaCommonTest\Auth\Assertion;

use DvsaCommon\Auth\Assertion\RefuseToTestAssertion;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommonTest\TestUtils\XMock;

class RefuseToTestAssertionTest extends \PHPUnit_Framework_TestCase
{
    private $authService;

    public function setUp()
    {
        $this->authService = XMock::of(MotAuthorisationServiceInterface::class);
    }

    /** @return  RefuseToTestAssertion */
    private function createAssertion()
    {
        return new RefuseToTestAssertion($this->authService);
    }

    public function testIsGrantedReturnsTrueWhenUserCanRefuseToTest()
    {
        $siteId = 1;
        $this->sitePermissionGranted($this->authService, $siteId);
        $this->globalPermissionGranted($this->authService);

        $this->assertTrue($this->createAssertion()->isGranted($siteId));
    }

    public function testIsGrantedReturnsFalseWhenUserIsNotGrantedAtSite()
    {
        $siteId = 1;
        $this->sitePermissionDenied($this->authService, $siteId);

        $this->assertFalse($this->createAssertion()->isGranted($siteId));
    }

    public function testIsGrantedReturnsFalseWhenUserIsNotGranted()
    {
        $this->globalPermissionDenied($this->authService);

        $this->assertFalse($this->createAssertion()->isGranted($siteId = 1));
    }

    /** @expectedException \DvsaCommon\Exception\UnauthorisedException */
    public function testAssertGrantedThrowsExeptionWhenUserCannotRefuseToTest()
    {
        $this->globalPermissionDenied($this->authService);

        $this->createAssertion()->assertGranted($siteId = 1);
    }

    private function sitePermissionGranted($authService, $siteId)
    {
        $authService->expects($this->atLeastOnce())->method('assertGrantedAtSite')
            ->with(PermissionAtSite::MOT_TEST_REFUSE_TEST_AT_SITE, $siteId);
    }

    private function sitePermissionDenied($authService, $siteId)
    {
        $authService->expects($this->atLeastOnce())->method('assertGrantedAtSite')
            ->with(PermissionAtSite::MOT_TEST_REFUSE_TEST_AT_SITE, $siteId)
            ->willThrowException(new UnauthorisedException(''));
    }

    private function globalPermissionGranted($authService)
    {
        $authService->expects($this->atLeastOnce())->method('assertGranted')
            ->with(PermissionInSystem::MOT_TEST_REFUSE_TEST);
    }

    private function globalPermissionDenied($authService)
    {
        $authService->expects($this->atLeastOnce())->method('assertGranted')
            ->with(PermissionInSystem::MOT_TEST_REFUSE_TEST)
            ->willThrowException(new UnauthorisedException(''));
    }
}