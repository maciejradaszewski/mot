<?php

namespace UserApiTest\Dashboard\Dto;

use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\Auth\AuthorisationServiceMock;
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
        $motTestType = MotTestTypeCode::NORMAL_TEST;

        $dashboard = new DashboardData([], $specialNotice, [], 3, 4, true, true, $motTestType, $authorisationMock);
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
            && isset($data['inProgressDemoTestNumber'])
        );
    }
}
