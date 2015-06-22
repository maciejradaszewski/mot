<?php
namespace UserApiTest\Dashboard\Dto;

use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\Auth\AuthorisationServiceMock;
use DvsaCommonTest\TestUtils\XMock;
use UserApi\Dashboard\Dto\DashboardData;

/**
 * Unit tests for Special notice dto
 */
class DashboardDataTest extends AbstractServiceTestCase
{
    public function test_toArray_basicData_shouldBeOk()
    {
        $specialNotice = SpecialNoticeTest::getInputUnreadOverdueDeadline();
        $authorisationMock = AuthorisationServiceMock::grantedAll();
        $dashboard = new DashboardData([], [], $specialNotice, [], [], 3, true, true, $authorisationMock);
        $this->assertWellFormedData($dashboard->toArray());
    }

    private function assertWellFormedData($data)
    {
        $this->assertTrue(
            is_array($data)
            && isset($data['hero'])
            && isset($data['authorisedExaminers'])
            && is_array($data['authorisedExaminers'])
            && isset($data['specialNotice'])
            && is_array($data['specialNotice'])
            && isset($data['notifications'])
            && is_array($data['notifications'])
            && isset($data['inProgressTestNumber'])
        );
    }
}
