<?php

namespace UserApi\Dashboard\Service;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommonApi\Service\AbstractService;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Person;
use UserApi\Dashboard\Dto\DayStats;
use UserApi\Dashboard\Dto\MonthStats;

/**
 * Service to gather a tester's statistics
 */
class UserStatsService extends AbstractService
{

    public function __construct(
        EntityManager $entityManager
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @param $personId
     *
     * @return DayStats
     */
    public function getUserDayStatsByPersonId($personId)
    {
        //TODO: OPENAM - confirm the current user is allowed to access these stats
        $person = $this->findOrThrowException(Person::class, $personId, Person::ENTITY_NAME);

        $motTests = $this->getTestsCompletedByTesterFromCompletedDate($person, DateUtils::today());

        $dayStats = $this->calculateDayStats($motTests);

        return $dayStats;
    }

    /**
     * @param $personId
     *
     * @return MonthStats
     */
    public function getUserCurrentMonthStatsByPersonId($personId)
    {
        //TODO: OPENAM - confirm the current user is allowed to access these stats
        $person = $this->findOrThrowException(Person::class, $personId, Person::ENTITY_NAME);

        $motTests = $this->getTestsCompletedByTesterFromCompletedDate($person, DateUtils::firstOfThisMonth(), true);

        $monthStats = $this->calculateMonthStats($motTests);

        return $monthStats;
    }

    /**
     * @param      $person
     * @param      $startDate
     * @param bool $onlyNormalTests
     *
     * @return MotTest[]
     */
    private function getTestsCompletedByTesterFromCompletedDate($person, $startDate, $onlyNormalTests = false)
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->getRepository(\DvsaEntities\Entity\MotTest::class)->createQueryBuilder('t');
        $queryBuilder->join(
            \DvsaEntities\Entity\MotTestType::class,
            'tt',
            Query\Expr\Join::INNER_JOIN,
            't.motTestType = tt.id'
        );
        $queryBuilder->where('t.tester = :person');
        $queryBuilder->andWhere('t.completedDate >= :startDate');
        $queryBuilder->andWhere('t.completedDate IS NOT NULL');
        $queryBuilder->setParameter('person', $person);
        $queryBuilder->setParameter('startDate', $startDate);

        if ($onlyNormalTests) {
            $queryBuilder->andWhere("tt.code IN (:SLOT_TEST_TYPES)");
            $queryBuilder->setParameter('SLOT_TEST_TYPES', MotTestTypeCode::NORMAL_TEST);
        } else {
            $queryBuilder->andWhere("tt.code NOT IN (:SLOT_TEST_TYPES)");
            $queryBuilder->setParameter(
                'SLOT_TEST_TYPES',
                [MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING, MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST]
            );
        }

        $motTests = $queryBuilder->getQuery()->getResult();

        return $motTests;
    }

    /**
     * @param MotTest[] $motTests
     *
     * @return DayStats
     */
    private function calculateDayStats($motTests)
    {
        $total = count($motTests);
        $passCount = 0;
        $failCount = 0;
        $retestCount = 0;

        foreach ($motTests as $motTest) {
            if ($motTest->getStatus() == MotTestStatusName::PASSED) {
                $passCount++;
            } elseif ($motTest->getStatus() == MotTestStatusName::FAILED) {
                $failCount++;
            }
            if ($motTest->getMotTestType()->getCode() === MotTestTypeCode::RE_TEST) {
                $retestCount++;
            }
        }

        $result = new DayStats($total, $passCount, $failCount, $retestCount);

        return $result;
    }

    /**
     * @param MotTest[] $motTests
     *
     * @return MonthStats
     */
    private function calculateMonthStats($motTests)
    {
        $total = count($motTests);
        $sumOfTestTimes = 0;
        $failCount = 0;
        $failRate = 0;
        $averageTestTime = 0;

        foreach ($motTests as $motTest) {
            if ($motTest->getStatus() == MotTestStatusName::FAILED) {
                $failCount++;
            }

            $testTime = DateUtils::getTimeDifferenceInSeconds($motTest->getCompletedDate(), $motTest->getStartedDate());
            $sumOfTestTimes += $testTime;
        }

        if ($total) {
            $failRate = $failCount * 100 / $total;
        }

        if ($total) {
            $averageTestTime = $sumOfTestTimes / $total;
        }

        $monthStats = new MonthStats($averageTestTime, $failRate);

        return $monthStats;
    }
}
