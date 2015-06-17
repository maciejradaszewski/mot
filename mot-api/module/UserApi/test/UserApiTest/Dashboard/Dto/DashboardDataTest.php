<?php
namespace UserApiTest\Dashboard\Dto;

use UserApi\Dashboard\Dto\DashboardData;

/**
 * Unit tests for Special notice dto
 */
class DashboardDataTest extends \PHPUnit_Framework_TestCase
{
    public function test_toArray_basicData_shouldBeOk()
    {
        $specialNotice = SpecialNoticeTest::getInputUnreadOverdueDeadline();
        $dashboard = new DashboardData([], [], $specialNotice, [], [], 3, true, true);
        $this->assertWellFormedData($dashboard->toArray());
    }

    private function assertWellFormedData($data)
    {
        $this->assertTrue(
            is_array($data)
            && isset($data['hero'])
            && isset($data['authorisedExaminers'])
            && is_array($data['authorisedExaminers'])
            && isset($data['permissions'])
            && is_array($data['permissions'])
            && isset($data['specialNotice'])
            && is_array($data['specialNotice'])
            && isset($data['notifications'])
            && is_array($data['notifications'])
            && isset($data['inProgressTestNumber'])
        );
    }
}
