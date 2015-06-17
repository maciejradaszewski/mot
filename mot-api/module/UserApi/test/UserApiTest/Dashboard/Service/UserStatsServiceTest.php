<?php

namespace UserApiTest\Dashboard\Service;

use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestStatus;
use DvsaEntities\Entity\Person;
use UserApi\Dashboard\Dto\DayStats;
use UserApi\Dashboard\Dto\MonthStats;
use UserApi\Dashboard\Service\UserStatsService;

/**
 * Tests for UserStatsService
 */
class UserStatsServiceTest extends AbstractServiceTestCase
{
    /**
     * @var UserStatsService $statsService
     */
    public $statsService;

    private $personId = 9;

    public function testConstructor()
    {
        $motTests = $this->getArrayOfMotTests();
        $this->setUpMocks($motTests);

        $this->assertEquals(get_class($this->statsService), UserStatsService::class);
    }

    public function testGetUserDayStatsByPersonId()
    {
        //given
        $motTests = $this->getArrayOfMotTestsWithRetests();
        $this->setUpMocks($motTests);

        //when
        $result = $this->statsService->getUserDayStatsByPersonId($this->personId);

        //then
        $this->assertEquals(get_class($result), DayStats::class);
        $this->assertEquals(
            $result->toArray(),
            [
                'total'           => 3,
                'numberOfPasses'  => 2,
                'numberOfFails'   => 1,
                'numberOfRetests' => 1,
            ]
        );
    }

    public function testGetUserCurrentMonthStatsByPersonId()
    {
        //given
        $motTests = $this->getArrayOfMotTests();
        $this->setUpMocks($motTests);

        //when
        $result = $this->statsService->getUserCurrentMonthStatsByPersonId($this->personId);

        //then
        $this->assertEquals(get_class($result), MonthStats::class);
        $this->assertEquals(
            $result->toArray(),
            [
                'averageTime' => 5400,
                'failRate'    => 50,
            ]
        );
    }

    private function getArrayOfMotTests()
    {
        $mtt = new \DvsaEntities\Entity\MotTestType();
        $mtt->setCode(MotTestTypeCode::NORMAL_TEST);
        $motTestPassed = new MotTest();
        $motTestPassed->setStatus($this->createMotTestStatus(MotTestStatusName::PASSED))
            ->setMotTestType($mtt)
            ->setStartedDate(new \DateTime('2014-07-01 10:00:00'))
            ->setCompletedDate(new \DateTime('2014-07-01 11:00:00'));

        $motTestFailed = new MotTest();
        $motTestFailed->setStatus($this->createMotTestStatus(MotTestStatusName::FAILED))
            ->setMotTestType($mtt)
            ->setStartedDate(new \DateTime('2014-07-01 11:00:00'))
            ->setCompletedDate(new \DateTime('2014-07-01 13:00:00'));

        $mtt = new \DvsaEntities\Entity\MotTestType();
        $mtt->setCode(MotTestTypeCode::RE_TEST);
        $motTestRetest = new MotTest();
        $motTestRetest->setStatus($this->createMotTestStatus(MotTestStatusName::PASSED))
            ->setMotTestType($mtt)
            ->setStartedDate(new \DateTime('2014-07-01 14:00:00'))
            ->setCompletedDate(new \DateTime('2014-07-01 14:30:00'));

        return [$motTestPassed, $motTestFailed];
    }

    private function getArrayOfMotTestsWithRetests()
    {
        $mtt = new \DvsaEntities\Entity\MotTestType();
        $mtt->setCode(MotTestTypeCode::RE_TEST);
        $motTestRetest = new MotTest();
        $motTestRetest->setStatus($this->createMotTestStatus(MotTestStatusName::PASSED))
            ->setMotTestType($mtt)
            ->setStartedDate(new \DateTime('2014-07-01 14:00:00'))
            ->setCompletedDate(new \DateTime('2014-07-01 14:30:00'));

        return array_merge($this->getArrayOfMotTests(), [$motTestRetest]);
    }

    private function setUpMocks($motTests)
    {
        $person = new Person();

        $mockQuery = $this->getMockBuilder(\Doctrine\ORM\AbstractQuery::class)
            ->setMethods(['setParameter', 'getResult'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->setupMockForCalls($mockQuery, 'getResult', $motTests);

        $mockQueryBuilder = $this->getMock(\Doctrine\ORM\QueryBuilder::class, [], [], '', false);
        $this->setupMockForCalls($mockQueryBuilder, 'getQuery', $mockQuery);

        $mockRepository = $this->getMockRepository();
        $this->setupMockForCalls($mockRepository, 'matching', $motTests);
        $this->setupMockForCalls($mockRepository, 'createQueryBuilder', $mockQueryBuilder, 't');

        $mockEntityManager = $this->getMockEntityManager();
        $this->setupMockForCalls($mockEntityManager, 'find', $person, Person::class);
        $this->setupMockForCalls($mockEntityManager, 'getRepository', $mockRepository, MotTest::class);

        $this->statsService = new UserStatsService($mockEntityManager);
    }

    private function createMotTestStatus($name)
    {
        $status = XMock::of(MotTestStatus::class);
        $status
            ->expects($this->any())
            ->method("getName")
            ->willReturn($name);

        return $status;
    }
}
