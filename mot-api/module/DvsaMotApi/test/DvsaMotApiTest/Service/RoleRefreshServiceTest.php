<?php
namespace DvsaMotApiTest\Service;

use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaMotApi\Service\RoleRefreshService;

/**
 * Class RoleRefreshServiceTest
 */
class RoleRefreshServiceTest extends AbstractServiceTestCase
{
    public function testRefreshRolesWhenNoRolesRefreshedShouldReturnFalse()
    {
        //given
        $userId = 123;
        $nonRefreshingRefresher1 = $this->createRoleRefresherReturning($userId, false);
        $nonRefreshingRefresher2 = $this->createRoleRefresherReturning($userId, false);
        $sut = new RoleRefreshService(
            [
                $nonRefreshingRefresher1,
                $nonRefreshingRefresher2
            ]
        );

        //when
        $result = $sut->refreshRoles($userId);

        //then
        $this->assertFalse($result);
    }

    public function testRefreshRolesWhenAnyRoleRefreshedShouldReturnTrue()
    {
        //given
        $userId = 534;
        $nonRefreshingRefresher1 = $this->createRoleRefresherReturning($userId, false);
        $refreshingRefresher2 = $this->createRoleRefresherReturning($userId, true);
        $sut = new RoleRefreshService(
            [
                $nonRefreshingRefresher1,
                $refreshingRefresher2
            ]
        );

        //when
        $result = $sut->refreshRoles($userId);

        //then
        $this->assertTrue($result);
    }

    private function createRoleRefresherReturning($userId, $roleRefreshed)
    {
        $mockRoleRefresher = $this->getMock(\DvsaMotApi\Service\RoleRefresher\RoleRefresherInterface::class);
        $mockRoleRefresher->expects($this->once())
            ->method('refresh')
            ->with($userId)
            ->will($this->returnValue($roleRefreshed));

        return $mockRoleRefresher;
    }
}
