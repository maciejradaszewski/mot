<?php

namespace DvsaCommonTest\TestUtils;

use DvsaCommon\Exception\UnauthorisedException;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

trait TestCasePermissionTrait
{
    protected function mockAssertGrantedAtSite(MockObj $authService, $permissions, $siteId)
    {
        $authService->expects($this->any())
            ->method("assertGrantedAtSite")
            ->willReturnCallback(
                function ($chkPermission, $chkSiteId) use (&$permissions, $siteId) {
                    if ($chkSiteId == $siteId && !in_array($chkPermission, $permissions)) {
                        throw new UnauthorisedException('You not have permissions');
                    }

                    return true;
                }
            );
    }

    protected function mockIsGrantedAtSite(MockObj $authService, $permissions, $siteId)
    {
        $authService->expects($this->any())
            ->method("isGrantedAtSite")
            ->willReturnCallback(
                function ($chkPermission, $chkSiteId) use (&$permissions, $siteId) {
                    return ($chkSiteId == $siteId && in_array($chkPermission, $permissions));
                }
            );
    }

    protected function mockAssertGrantedAtOrganisation(MockObj $authService, $permissions, $orgId)
    {
        $authService->expects($this->any())
            ->method("assertGrantedAtOrganisation")
            ->willReturnCallback(
                function ($chkPermission, $chkOrgId) use (&$permissions, $orgId) {
                    if ($chkOrgId === $orgId && !in_array($chkPermission, $permissions)) {
                        throw new UnauthorisedException('You not have permissions');
                    }

                    return true;
                }
            );
    }

    protected function mockIsGrantedAtOrganisation(MockObj $authService, $permissions, $orgId)
    {
        $authService->expects($this->any())
            ->method("isGrantedAtOrganisation")
            ->willReturnCallback(
                function ($chkPermission, $chkOrgId) use (&$permissions, $orgId) {
                    return ($chkOrgId == $orgId && in_array($chkPermission, $permissions));
                }
            );
    }

    protected function mockAssertGranted(MockObj $authService, $permissions)
    {
        $authService->expects($this->any())
            ->method("assertGranted")
            ->willReturnCallback(
                function ($arg) use (&$permissions) {
                    if (!in_array($arg, $permissions)) {
                        throw new UnauthorisedException('You not have permissions');
                    }

                    return true;
                }
            );
    }

    protected function mockIsGranted(MockObj $authService, $permissions)
    {
        $authService->expects($this->any())
            ->method("isGranted")
            ->willReturnCallback(
                function ($arg) use (&$permissions) {
                    return in_array($arg, $permissions);
                }
            );
    }
}
