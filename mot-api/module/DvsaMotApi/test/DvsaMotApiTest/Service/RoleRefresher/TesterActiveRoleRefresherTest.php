<?php
namespace DvsaMotApiTest\Service\RoleRefresher;

use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaMotApi\Service\TesterService;
use DvsaMotApi\Service\RoleRefresher\TesterActiveRoleRefresher;

/**
 * Class TesterActiveRoleRefresherTest
 */
class TesterActiveRoleRefresherTest extends AbstractServiceTestCase
{
    /**
     * @var TesterActiveRoleRefresher $sut
     */
    private $sut;
    private $mockTesterService;

    public function setUp()
    {
        $this->mockTesterService = $this->getMockWithDisabledConstructor(\DvsaMotApi\Service\TesterService::class);
        $this->sut = new TesterActiveRoleRefresher($this->mockTesterService);
    }
    public function testRefreshWhenUserNotFoundShouldReturnFalse()
    {
        //given
        $userId = 12312;
        $this->mockTesterService->expects($this->once())
            ->method('isTester')
            ->with($userId)
            ->will($this->returnValue(false));

        //when
        $result = $this->sut->refresh($userId);

        //then
        $this->assertFalse($result);
    }

    /**
     * @dataProvider verificationResultData
     */
    public function testRefreshWhenUserFoundShouldReturnVerificationResult($verificationResult)
    {
        //given
        $userId = 12312;
        $this->mockTesterService->expects($this->once())
            ->method('isTester')
            ->with($userId)
            ->will($this->returnValue(true));
        $this->mockTesterService->expects($this->once())
            ->method('verifyAndApplyTesterIsActiveByUserId')
            ->with($userId)
            ->will($this->returnValue($verificationResult));

        //when
        $result = $this->sut->refresh($userId);

        //then
        $this->assertEquals($verificationResult, $result);
    }

    /**
     * All possible values returned by verifyAndApplyTesterIsActiveByUserId
     * @return array
     */
    public static function verificationResultData()
    {
        return [
            [true],
            [false]
        ];
    }
}
