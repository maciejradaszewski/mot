<?php

namespace DvsaEntities\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Dto\MotTesting\ContingencyMotTestDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommonApi\Model\SearchParam;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaCommonApi\Service\Exception\ServiceException;
use DvsaEntities\DqlBuilder\NativeQueryBuilder;
use DvsaEntities\DqlBuilder\SearchParam\MotTestSearchParam;
use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestType;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\Vehicle;

/**
 * Class MotTestRepository
 *
 * @package DvsaEntities\Repository
 * @codeCoverageIgnore
 */
class MotTestRepository extends AbstractMutableRepository
{
    protected $query;

    /**
     * Test types used commonly in queries.
     *
     * @var array
     */
    private $testTypes
        = [
            MotTestTypeCode::RE_TEST,
            MotTestTypeCode::NORMAL_TEST,
            MotTestTypeCode::STATUTORY_APPEAL,
            MotTestTypeCode::INVERTED_APPEAL,
        ];

    /**
     * @param $vehicleId
     * @param $contingencyDto
     *
     * @return MotTest
     */
    public function findLastNormalTest($vehicleId, $contingencyDto = null)
    {
        $qb = $this
            ->createQueryBuilder("mt")
            ->innerJoin("mt.vehicle", "v")
            ->innerJoin("mt.motTestType", "t")
            ->where("v.id = :vehicleId")
            ->andWhere("t.code = :code")
            ->orderBy("mt.completedDate", "DESC")
            ->setParameter("vehicleId", $vehicleId)
            ->setParameter("code", MotTestTypeCode::NORMAL_TEST)
            ->setMaxResults(1);

        if ($contingencyDto instanceof ContingencyMotTestDto) {
            $qb
                ->andWhere("mt.completedDate <= :contingencyDate")
                // Time changed to 23:59:59, because if normal test and CT test have a same date, and because
                // normal test has a TIME, the query can't find a normal test, because NT DateTime >= CT Date + 00:00:00
                ->setParameter("contingencyDate", $contingencyDto->getPerformedAt() . ' 23:59:59');
        }

        $resultArray = $qb->getQuery()->getResult();

        return empty($resultArray) ? null : $resultArray[0];
    }

    /**
     * @param $registration
     * @param $v5cReference
     *
     * @throws \DvsaCommonApi\Service\Exception\ServiceException
     */
    public function findLastPass($registration, $v5cReference)
    {
        $exception = new ServiceException(null);
        $exception->addError(
            "TODO(PT): To be implemented when a placement of v5c is known.",
            ServiceException::DEFAULT_STATUS_CODE
        );

        throw $exception;
    }

    /**
     * Returns the expiry date for the last previous certificated test.
     * Can be null if no such certificate has been issued.
     *
     * @param $vehicleId
     *
     * @return \DateTime
     */
    public function findLastCertificateExpiryDate($vehicleId)
    {
        $qb = $this
            ->createQueryBuilder("mt")
            ->innerJoin("mt.motTestType", "t")
            ->innerJoin("mt.status", "ts")
            ->where("mt.vehicle = :vehicleId")
            ->andWhere("t.code IN (:testTypes)")
            ->andWhere("ts.name = :status")
            ->orderBy("mt.expiryDate", "DESC")
            ->setParameter("vehicleId", $vehicleId)
            ->setParameter("status", MotTestStatusName::PASSED)
            ->setParameter("testTypes", $this->testTypes)
            ->setMaxResults(1);

        $resultArray = $qb->getQuery()->getArrayResult();

        return empty($resultArray) ? null : $resultArray[0]['expiryDate'];
    }

    /**
     * Finds in progress MOT test number for a person
     *
     * @param $personId
     *
     * @return int
     */
    public function findInProgressTestNumberForPerson($personId)
    {
        $motTest = $this->findInProgressTestForPerson($personId);

        return is_null($motTest) ? null : $motTest->getNumber();
    }

    /**
     * Finds in progress MOT test number for a person
     *
     * @param $personId
     *
     * @return MotTest
     */
    public function findInProgressTestForPerson($personId)
    {
        $qb = $this
            ->createQueryBuilder("mt")
            ->innerJoin("mt.motTestType", "t")
            ->innerJoin("mt.status", "ts")
            ->where("mt.tester = :personId")
            ->andWhere("ts.name = :status")
            ->andWhere("t.code NOT IN (:code)")
            ->setParameter("personId", $personId)
            ->setParameter("status", MotTestStatusName::ACTIVE)
            ->setParameter(
                "code",
                [MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST, MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING]
            )
            ->setMaxResults(1);

        $resultArray = $qb->getQuery()->getResult();

        return empty($resultArray) ? null : $resultArray[0];
    }

    private function findInProgressTestDataForVehicle($vehicleId, $selectClause)
    {
        $demoTestTypes = [
            MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST,
            MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING
        ];
        $qb = $this->_em->createQueryBuilder();
        $testNotADemo = $qb->expr()->notIn('t.code', $demoTestTypes);

        return $qb->select($selectClause)
            ->from(MotTest::class, 'mt')
            ->join('mt.motTestType', 't')
            ->join('mt.status', 'ts')
            ->where($testNotADemo)
            ->andWhere('ts.name = :status')
            ->andWhere('mt.vehicle = :vehicleId')
            ->getQuery()
            ->setMaxResults(1)
            ->setParameter('vehicleId', $vehicleId)
            ->setParameter('status', MotTestStatusName::ACTIVE)
            ->getResult();
    }

    /**
     * @param $vehicleId
     *
     * @return integer
     */
    public function isTestInProgressForVehicle($vehicleId)
    {
        $resultArray = $this->findInProgressTestDataForVehicle($vehicleId, 'mt.id');
        return !empty($resultArray[0]['id']);
    }

    /**
     * @param $vehicleId
     *
     * @return MotTest
     */
    public function findInProgressTestForVehicle($vehicleId)
    {
        $resultArray = $this->findInProgressTestDataForVehicle($vehicleId, 'mt');
        return empty($resultArray) ? null : $resultArray[0];
    }

    /**
     * @param $vtsId
     *
     * @return MotTest[]
     */
    public function findInProgressTestsForVts($vtsId)
    {
        $qb = $this->createQueryBuilder("mt");
        $query =
            $qb
            ->innerJoin("mt.status", "ts")
            ->where("ts.name = :status")
            ->andWhere("mt.vehicleTestingStation = :vehicleTestingStation")
            ->setParameter("status", MotTestStatusName::ACTIVE)
            ->setParameter("vehicleTestingStation", $vtsId)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @param $motTestNumber
     *
     * @return MotTest
     */
    public function findRetestForMotTest($motTestNumber)
    {
        $qb = $this
            ->createQueryBuilder("mt")
            ->innerJoin("mt.motTestIdOriginal", "omt")
            ->innerJoin("mt.motTestType", "t")
            ->innerJoin("mt.status", "ts")
            ->where("t.code = :code")
            ->andWhere("omt.number = :normalTestId")
            ->andWhere("ts.name IN (:statuses)")
            ->setParameter("normalTestId", $motTestNumber)
            ->setParameter("code", MotTestTypeCode::RE_TEST)
            ->setParameter("statuses", [MotTestStatusName::PASSED, MotTestStatusName::FAILED])
            ->setMaxResults(1);

        $resultArray = $qb->getQuery()->getResult();

        return empty($resultArray) ? null : $resultArray[0];
    }

    public function getLatestMotTestsBySiteNumber($siteNumber)
    {
        $mtQb =  $this
            ->createQueryBuilder("it")
            ->select("DATE(it.startedDate) AS sort_date")
            ->distinct(true)
            ->innerJoin("it.vehicleTestingStation", "ivts")
            ->where("ivts.siteNumber = :siteNumber")
            ->andWhere("it.startedDate >= DATE_SUB(CURRENT_DATE(), 2, 'MONTH')")
            ->addOrderBy("sort_date", "DESC")
            ->setParameter("siteNumber", $siteNumber)
            ->setMaxResults(2);

        $res = $mtQb->getQuery()->getArrayResult();
        $dates = array_map(
            function ($data) {
                return $data['sort_date'];
            }, $res
        );

        if (empty($dates)) {
            return new ArrayCollection();
        }

        $minDate = min($dates);

        $qb = $this
            ->createQueryBuilder("t")
            ->select(["t", "v", "o", "c1", "p", "vts", "tt", "ts", "vc", "ma", "mo"])
            ->innerJoin("t.tester", "p")
            ->innerJoin("t.vehicle", "v")
            ->innerJoin("t.vehicleTestingStation", "vts")
            ->innerJoin("v.vehicleClass", "vc")
            ->leftJoin("t.make", "ma")
            ->leftJoin("t.model", "mo")
            ->innerJoin("t.primaryColour", "c1")
            ->innerJoin("t.motTestType", "tt")
            ->innerJoin("t.status", "ts")
            ->leftJoin("t.odometerReading", "o")
            ->where("vts.siteNumber = :siteNumber")
            ->setParameter("siteNumber", $siteNumber)
            ->orderBy("t.startedDate", "DESC")
            ->addOrderBy("v.id", "DESC")
            ->andWhere("t.startedDate >= :minDate")
            ->setParameter("minDate", $minDate);

        return $qb->getQuery()->getResult();
    }

    /**
     * Based on MOT test certificate number returns common MOT test data
     *
     * @param $motTestNumber
     *
     * @return MotTest
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getMotTestByNumber($motTestNumber)
    {
        $result = $this->createQueryBuilder('mt')
            ->select('mt, tt, s, rfr')
            ->innerJoin('mt.motTestType', 'tt')
            ->innerJoin('mt.status', 's')
            ->leftJoin('mt.motTestReasonForRejections', 'rfr')
            ->where('mt.number = :number')
            ->setParameters(['number' => $motTestNumber])
            ->getQuery()->getOneOrNullResult();

        if (is_null($result)) {
            throw new NotFoundException("MOT Test with number $motTestNumber");
        }

        return $result;
    }

    /**
     * Returns a test for a given registration and test number
     *
     * @param $registration
     * @param $testNumber
     *
     * @return MotTest
     */
    public function findTestByVehicleRegistrationAndTestNumber($registration, $testNumber)
    {
        $qb = $this
            ->createQueryBuilder("mt")
            ->innerJoin("mt.motTestType", "t")
            ->innerJoin("mt.status", "ts")
            ->where("ts.name IN (:statuses)")
            ->andWhere("t.code IN (:testTypes)")
            ->andWhere("mt.registration = :registration")
            ->andWhere("mt.number = :number")
            ->setParameter("statuses", [MotTestStatusName::PASSED, MotTestStatusName::FAILED])
            ->setParameter("testTypes", $this->testTypes)
            ->setParameter("registration", $registration)
            ->setParameter("number", $testNumber);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Returns a list of tests for a given vehicle as of a specified date
     *
     * @param $vehicleId
     * @param $startDate
     *
     * @return MotTest[]
     */
    public function findHistoricalTestsForVehicle($vehicleId, $startDate)
    {
        $statuses = [
            MotTestStatusName::PASSED,
            MotTestStatusName::FAILED,
            MotTestStatusName::ABANDONED
        ];

        $qb = $this
            ->createQueryBuilder("mt")
            ->innerJoin("mt.motTestType", "t")
            ->innerJoin("mt.status", "ts")
            ->where("ts.name IN (:statuses)")
            ->andWhere("t.code IN (:testTypes)")
            ->andWhere("mt.vehicle = :vehicleId")
            ->orderBy("mt.issuedDate", "DESC")
            ->addOrderBy("mt.completedDate", "DESC")
            ->addOrderBy("mt.vehicleTestingStation", "ASC")
            ->setParameter("statuses", $statuses)
            ->setParameter("testTypes", $this->testTypes)
            ->setParameter("vehicleId", $vehicleId);

        if ($startDate !== null) {
            $qb
                ->andWhere("mt.issuedDate >= :startDate")
                ->setParameter("startDate", $startDate);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Checks if there is any latest Fail, Abandon, Aborted or Refused test of a vehicle issued after given test.
     *
     * @param $registration
     * @param $v5cReference
     * @param $issuedDate
     *
     * @throws ServiceException
     */
    public function findNonPassIssuedAfter($registration, $v5cReference, $issuedDate)
    {
        $exception = new ServiceException(null);
        $exception->addError(
            "TODO(PT): To be implemented when a placement of v5c is known.",
            ServiceException::DEFAULT_STATUS_CODE
        );

        throw $exception;
    }

    /**
     * Checks if there is latest test of a vehicle with a different V5C reference number and issued after given test.
     *
     * @param $registration
     * @param $v5cReference
     * @param $issuedDate
     *
     * @throws \DvsaCommonApi\Service\Exception\ServiceException
     */
    public function isAnyWithDifferentV5cReferenceIssuedAfter($registration, $v5cReference, $issuedDate)
    {
        $exception = new ServiceException(null);
        $exception->addError(
            "TODO(PT): To be implemented when a placement of v5c is known.",
            ServiceException::DEFAULT_STATUS_CODE
        );

        throw $exception;
    }

    /**
     * Get the latest MOT Test by vehicleId and Result
     *
     * @param string $vehicleId
     * @param string $status
     * @param string $issuedDate
     *
     * @return \DvsaEntities\Entity\MotTest
     */
    public function getLatestMotTestByVehicleIdAndResult(
        $vehicleId,
        $status = MotTestStatusName::PASSED,
        $issuedDate
    ) {
        $qb = $this
            ->createQueryBuilder("t")
            ->innerJoin("t.motTestType", "tt")
            ->innerJoin("t.status", "ts")
            ->where("t.vehicle = :vehicleId")
            ->andWhere("t.completedDate IS NOT NULL")
            ->andWhere("tt.code NOT IN (:codes)")
            ->andWhere("ts.name = :status")
            ->andWhere("t.issuedDate < :issuedDate")
            ->orderBy("t.completedDate", "DESC")
            ->setParameter('vehicleId', $vehicleId)
            ->setParameter('status', $status)
            ->setParameter('issuedDate', $issuedDate)
            ->setParameter('codes', ['DR', 'DT'])
            ->setMaxResults(1);
        ;

        $result = $qb->getQuery()->getResult();

        return array_shift($result);
    }

    /**
     * Get the latest MOT Test by registration number and Result
     *
     * @param string $vrm
     * @param string $status
     * @param string $issuedDate
     * @param array $excludeCodes
     *
     * @return \DvsaEntities\Entity\MotTest
     */
    public function findLatestMotTestByVrmAndResult(
        $vrm,
        $status = MotTestStatusName::PASSED,
        $issuedDate,
        $excludeCodes = [
            MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING,
            MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST
        ]
    ) {
        $qb = $this
            ->createQueryBuilder("t")
            ->innerJoin("t.motTestType", "tt")
            ->innerJoin("t.status", "ts")
            ->innerJoin("t.vehicle", "v")
            ->where("v.registration = :vrm")
            ->andWhere("t.completedDate IS NOT NULL")
            ->andWhere("tt.code NOT IN (:codes)")
            ->andWhere("ts.name = :status")
            ->andWhere("t.issuedDate < :issuedDate")
            ->orderBy("t.completedDate", "DESC")
            ->setParameter('vrm', $vrm)
            ->setParameter('status', $status)
            ->setParameter('issuedDate', $issuedDate)
            ->setParameter('codes', $excludeCodes)
            ->setMaxResults(1);
        ;

        $result = $qb->getQuery()->getResult();

        return array_shift($result);
    }

    /**
     * Retrieve the latest MOT test number of a specific status by vehicle ID
     * @param int $vehicleId Non-obfuscated vehicle ID
     * @param string $status Status of MOT test to retrieve, default passed
     * @return string|null Numeric MOT test number or null if no test with $status exists for vehicle ID
     */
    public function getLatestMotTestIdByVehicleId($vehicleId, $status = MotTestStatusName::PASSED)
    {
        // Get the latest completed test date - this is used as a subquery
        // to prevent possible performance hit by doing ORDER BY
        $subQuery = $this->createQueryBuilder('t')
            ->select('MAX(t.completedDate)')
            ->innerJoin("t.motTestType", "tt")
            ->innerJoin("t.status", "ts")
            ->where("t.vehicle = :vehicleId")
            ->andWhere("t.completedDate IS NOT NULL")
            ->andWhere("tt.code NOT IN (:codes)")
            ->andWhere("ts.name = :status")
        ;

        // Get the MOT test number for the latest completed test with status $status for a vehicle
        $qb = $this->createQueryBuilder("t2");
        $qb->select('t2.number')
           ->where($qb->expr()->in('t2.completedDate', $subQuery->getDQL()))
           ->setParameter('vehicleId', $vehicleId)
           ->setParameter('status', $status)
           ->setParameter('codes', [
                MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING,
                MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST,
           ])
        ;

        if ($result = $qb->getQuery()->getResult()) {
            return $result[0]['number'];
        }

        throw new NotFoundException('MOT test with status ' . $status . ' for vehicle ' . $vehicleId);
    }

    /**
     * Get all complete and in-progress mot tests by vehicleId
     *
     * @param int $vehicleId
     * @param int $maxResults
     *
     * @return array [DvsaEntities\Entity\MotTest]
     */
    public function getLatestMotTestsByVehicleId($vehicleId, $maxResults = 100)
    {
        $qb = $this
            ->createQueryBuilder("mt")
            ->addSelect("v, vt, t, rfr")
            ->innerJoin("mt.vehicle", "v")
            ->innerJoin("mt.vehicleTestingStation", "vt")
            ->innerJoin("mt.tester", "t")
            ->innerJoin("mt.motTestType", "tt")
            ->leftJoin("mt.motTestReasonForRejections", "rfr")
            ->where("mt.vehicle = :vehicleId")
            ->orderBy("mt.startedDate", "DESC")
            ->setParameter('vehicleId', $vehicleId)
            ->setMaxResults($maxResults);

        return $qb->getQuery()->getResult();
    }

    /**
     * Retrieves MOT test entity and if it is not found throws an exception
     *
     * @param $id
     *
     * @return null|MotTest
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getMotTest($id)
    {
        $motTest = $this->find($id);
        if (!$motTest) {
            throw new NotFoundException("Mot Test", $id);
        }

        return $motTest;
    }

    /**
     * Get the odometer history for a given vehicle id
     *
     * @param int $vehicleId
     * @param int $limit (default = 4)
     *
     * @return array
     */
    public function getOdometerHistoryForVehicleId($vehicleId, $limit = 4)
    {
        $qb = $this->_em->createQueryBuilder();

        $codes = [
            MotTestTypeCode::RE_TEST,
            MotTestTypeCode::NORMAL_TEST,
            MotTestTypeCode::INVERTED_APPEAL,
            MotTestTypeCode::STATUTORY_APPEAL
        ];

        $qb->select('t.issuedDate, o.value, o.unit, ts.name as status, o.resultType as resultType, DATE(t.issuedDate) as dtIssuedDate')
            ->from($this->getEntityName(), 't')
            ->innerJoin('t.odometerReading', 'o')
            ->innerJoin('t.motTestType', 'tt')
            ->innerJoin('t.status', 'ts')
            ->where("t.vehicle = :vehicleId")
            ->andWhere("ts.name = :name")
            ->andWhere("tt.code IN (:codes)")
            ->orderBy('t.issuedDate', 'DESC')
            ->setMaxResults($limit);

        $qb->setParameter("vehicleId", $vehicleId)
            ->setParameter("name", MotTestStatusName::PASSED)
            ->setParameter("codes", $codes)
        ;

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Get odometer reading for id
     *
     * @param $id
     *
     * @return mixed
     */
    public function getOdometerReadingForId($id)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('o.value, o.unit, o.resultType')
            ->from($this->getEntityName(), 't')
            ->innerJoin("t.odometerReading", "o")
            ->where('t.id = ?0')
            ->setMaxResults(1);

        $qb->setParameter(0, $id);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $motTestId
     * @param string $v5c
     *
     * @return MotTest
     */
    public function findMotTestByMotTestIdAndV5c($motTestId, $v5c)
    {
        $qb = $this
            ->createQueryBuilder("mt")
            ->innerJoin("mt.vehicle", 'v')
            ->innerJoin("v.vehicleV5Cs", 'vv5c')
            ->andwhere('vv5c.lastSeen is null')
            ->andWhere('mt.id = :motTestId')
            ->andWhere('vv5c.v5cRef = :v5c')
            ->setMaxResults(1)
            ->setParameter('motTestId', $motTestId)
            ->setParameter('v5c', str_replace(' ', '', $v5c));

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param int $motTestId
     * @param string $motTestNumber
     *
     * @return bool
     */
    public function isMotTestNumberValidForMotTest($motTestId, $motTestNumber)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('mt.number')
            ->from($this->getEntityName(), 'mt')
            ->andwhere('mt.id = :motTestId')
            ->andWhere('mt.number = :motTestNumber')
            ->setMaxResults(1);

        $qb->setParameter('motTestId', $motTestId);
        $qb->setParameter('motTestNumber', str_replace(' ', '', $motTestNumber));

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result !== null;
    }

    /**
     * This function allow us to paginate all the database to avoid memory limit
     *
     * @param int $start
     * @param int $offset
     * @param string $orderBy
     * @param int $hydrateMode
     * @return array
     */
    public function getAllDataForEsIngestion(
        $start,
        $offset,
        $orderBy = 'test.id',
        $hydrateMode = Query::HYDRATE_OBJECT
    ) {
        $qb = $this
            ->createQueryBuilder("test")
            ->addSelect(["model", "make"])
            ->leftJoin("test.make", "make")
            ->leftJoin("test.model", "model")
            ->orderBy($orderBy);

        $paginate = new Paginator($qb->getQuery(), $fetchJoinCollection = true);
        $paginate
            ->getQuery()
            ->setFirstResult($start)
            ->setMaxResults($offset)
            ->setHydrationMode($hydrateMode);

        return $paginate;
    }

    /**
     * This function is responsible to get the number of mot test realised for
     * last 365 days, previous month, previous week and today
     *
     * @param int $organisationId
     *
     * @return array eg. ['year' => 1234, 'month' => 9999, 'week' => 8888, 'today' => 777]
     */
    public function getCountOfMotTestsSummary($organisationId)
    {
        $testTypes = [
            'TT_NORMAL'                  => MotTestTypeCode::NORMAL_TEST,
            'TT_PARTIAL_RETEST_LEFT'     => MotTestTypeCode::PARTIAL_RETEST_LEFT_VTS,
            'TT_PARTIAL_RETEST_REPAIRED' => MotTestTypeCode::PARTIAL_RETEST_REPAIRED_AT_VTS,
            'TT_RETEST'                  => MotTestTypeCode::RE_TEST,
        ];

        $statuses = [
            'TS_ABANDONED'  => MotTestStatusName::ABANDONED,
            'TS_ABORTED'    => MotTestStatusName::ABORTED,
            'TS_ABORTED_VE' => MotTestStatusName::ABORTED_VE,
            'TS_FAILED'     => MotTestStatusName::FAILED,
            'TS_PASSED'     => MotTestStatusName::PASSED,
            'TS_REFUSED'    => MotTestStatusName::REFUSED,
        ];

        $sql = '
            SELECT
                COUNT(t.id) AS `year`,
                SUM(
                    CASE WHEN coalesce(t.completed_date, t.started_date) BETWEEN
                             LAST_DAY(CURRENT_DATE() - INTERVAL 2 MONTH) + INTERVAL 1 DAY
                             AND LAST_DAY(CURRENT_DATE()) - INTERVAL 1 MONTH + INTERVAL 1 DAY
					THEN 1
                    ELSE 0
                    END
                ) AS `month`,
                SUM(
					CASE WHEN coalesce(t.completed_date, t.started_date) BETWEEN
							CURRENT_DATE() - INTERVAL WEEKDAY(CURRENT_DATE()) + 7 DAY
							AND CURRENT_DATE() - INTERVAL WEEKDAY(CURRENT_DATE()) DAY
					THEN 1
                    ELSE 0
                    END
                ) AS `week`,
                SUM(
                    CASE WHEN coalesce(t.completed_date, t.started_date) >= CURRENT_DATE()
                    THEN 1
                    ELSE 0
                    END
                ) AS `today`

            FROM
                site AS s

                INNER JOIN mot_test AS t ON
                    t.site_id = s.id

                INNER JOIN mot_test_type AS tt ON
                    tt.id = t.mot_test_type_id

                INNER JOIN mot_test_status AS ts ON
                    ts.id = t.status_id

            WHERE
                s.organisation_id = :ORGANISATION_ID
                AND (
                    (
                      t.completed_date IS NULL
                      AND t.started_date >= (CURRENT_DATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY)
                    )
                    OR (
                        t.completed_date IS NOT NULL
                        AND t.completed_date >= (CURRENT_DATE() - INTERVAL 1 YEAR + INTERVAL 1 DAY)
                    )
                )';

        //  --  add test type where clause --
        $whereParams = [];
        foreach ($testTypes as $key => $val) {
            $whereParams[] = ':' . $key;
        }
        $sql .= ' AND tt.code IN (' . join(', ', $whereParams) . ')';

        //  --  add test type where clause --
        $whereParams = [];
        foreach ($statuses as $key => $val) {
            $whereParams[] = ':' . $key;
        }
        $sql .= ' AND ts.name IN (' . join(', ', $whereParams) . ')';

        //  ----  prepare statement and bind params   ----
        $em = $this->getEntityManager();
        $sql = $em->getConnection()->prepare($sql);

        $sql->bindValue('ORGANISATION_ID', $organisationId);

        //  --  bind test types --
        foreach ($testTypes as $key => $val) {
            $sql->bindValue($key, $val);
        }

        //  --  bind statuses --
        foreach ($statuses as $key => $val) {
            $sql->bindValue($key, $val);
        }

        $sql->execute();

        return $sql->fetch();
    }

    /**
     * This function is responsible to get the number of mot test realise the
     * current day
     *
     * @param int $organisationId
     *
     * @return array eg. ['today' => 1234]
     */
    public function getNumberOfMotTestsForToday($organisationId)
    {
        $sql =
            '
            SELECT
              COUNT(test.id) as `today`
            FROM
              mot_test AS test
              INNER JOIN site AS site ON test.site_id = site.id
            WHERE
              site.organisation_id = :ORGANISATION_ID
              AND test.completed_date >= CURRENT_DATE()';

        $em = $this->getEntityManager();
        $sql = $em->getConnection()->prepare($sql);
        $sql->bindValue('ORGANISATION_ID', $organisationId);
        $sql->execute();

        return $sql->fetch();
    }

    /**
     * Prepare statement to get mot tests log data
     *
     * @param MotTestSearchParam $searchParam
     *
     * @return NativeQueryBuilder
     */
    public function prepareMotTest(MotTestSearchParam $searchParam)
    {
        //  --  prepare sub query   --
        $qb = new NativeQueryBuilder();
        $qb
            ->select('*', 'all')
            ->select(
                'COALESCE(mt.completed_date, mt.started_date) AS testDate,
                TIMESTAMPDIFF(SECOND, mt.started_date, COALESCE(mt.completed_date, mt.started_date)) as testDuration
                ',
                'additionalFields'
            )
            ->from('mot_test', 'mt')
            ->join('site', 's', 's.id = mt.site_id')
            ->join('make', 'vma', 'vma.id = mt.make_id', NativeQueryBuilder::JOIN_TYPE_LEFT)
            ->join('model', 'vmo', 'vmo.id = mt.model_id', NativeQueryBuilder::JOIN_TYPE_LEFT)
            ->join('mot_test_type', 'tt', 'tt.id = mt.mot_test_type_id')
            ->join('mot_test_status', 'ts', 'ts.id = mt.status_id')
            ->join('person', 'p', 'p.id = mt.person_id');

        if ($searchParam->getSiteNumber()) {
            $qb->andwhere('s.site_number = :SITE_NR')
                ->setParameter('SITE_NR', $searchParam->getSiteNumber());
        }

        if ($searchParam->getTesterId()) {
            $qb->andwhere('mt.person_id = :TESTER_ID')
                ->setParameter('TESTER_ID', $searchParam->getTesterId());
        }

        if ($searchParam->getRegistration()) {
            $qb->andwhere('mt.registration = :VRM')
                ->setParameter('VRM', $searchParam->getRegistration());
        }

        if ($searchParam->getVin()) {
            $qb->andwhere('mt.vin = :VIN')
                ->setParameter('VIN', $searchParam->getVin());
        }

        if ($searchParam->getVehicleId()) {
            $qb->andwhere('mt.vehicle_id = :VEHICLE_ID')
                ->setParameter('VEHICLE_ID', $searchParam->getVehicleId());
        }

        if ($searchParam->getOrganisationId()) {
            $qb->andwhere('s.organisation_id = :ORGANISATION_ID')
                ->setParameter('ORGANISATION_ID', $searchParam->getOrganisationId());
        }

        $statuses = $searchParam->getStatus();
        if (!empty($statuses)) {
            $query = [];
            foreach ($statuses as $key => $item) {
                $query[] = ':STATUS' . $key;

                $qb->setParameter('STATUS' . $key, $item);
            }

            $qb->andwhere('ts.name IN (' . join(',', $query) . ')');
        }

        $testType = $searchParam->getTestType();
        if (!empty($testType)) {
            $query = [];
            foreach ($testType as $key => $item) {
                $query[] = ':TEST_TYPE' . $key;

                $qb->setParameter('TEST_TYPE' . $key, $item);
            }

            $qb->andwhere('tt.code IN (' . join(',', $query) . ')');
        }

        if ($searchParam->getDateFrom() || $searchParam->getDateTo()) {
            $qb->andwhere('
                (
                    (
                    mt.completed_date IS NULL
                    AND mt.started_date BETWEEN :DATE_FROM AND :DATE_TO
                    )
                OR (
                    mt.completed_date IS NOT NULL
                    AND mt.completed_date BETWEEN :DATE_FROM AND :DATE_TO
                    )
                )
                ')
                ->setParameter('DATE_FROM', $searchParam->getDateFrom() ?: new \DateTime('1900-01-01'))
                ->setParameter('DATE_TO', $searchParam->getDateTo() ?: new \DateTime());
        }

        //  logical block: define order by statement
        $orderBy = $searchParam->getSortColumnNameDatabase();
        if (!empty($orderBy)) {
            if (!is_array($orderBy)) {
                $orderBy = [$orderBy];
            }

            foreach ($orderBy as $order) {
                $qb->orderBy($order . ' ' . $searchParam->getSortDirection());
            }
        }

        //  logical block: define pagination statement
        if ($searchParam->getStart() > 0) {
            $qb->setOffset($searchParam->getStart());
        }

        if ($searchParam->getRowCount() > 0) {
            $qb->setLimit($searchParam->getRowCount());
        }

        return $qb;
    }

    public function getMotTestLogsResult(SearchParam $searchParam)
    {
        $qb = $this->prepareMotTest($searchParam);
        $qb
            ->resetPart('select', 'all')
            ->select('mt.number, mt.client_ip, ts.name AS status')
            ->select('mt.registration, mt.vin')
            ->select('vma.code as make_code, COALESCE(vma.name, mt.make_name) AS makeName')
            ->select('vma.code as make_code, COALESCE(vmo.name, mt.model_name) AS modelName')
            ->select('vc.name AS vehicle_class')
            ->select('s.site_number AS siteNumber, p.username as userName, tt.description as testTypeName')
            ->select('mt.emergency_log_id AS emLogId')
            ->join('vehicle_class', 'vc', 'vc.id = mt.vehicle_class_id');

        if ($searchParam->getFormat() === SearchParamConst::FORMAT_DATA_CSV) {
            $qb
                ->select(
                    'CASE WHEN eml.id IS NOT NULL THEN emp.username ELSE NULL END AS emRecTester,
                    CASE WHEN eml.id IS NOT NULL THEN mt.created_on ELSE NULL END AS emRecDateTime,
                    COALESCE(emcm.comment, emrl.name) AS emReason,
                    eml.number AS emCode',
                    'emergency'
                )
                ->join('emergency_log', 'eml', 'eml.id = mt.emergency_log_id', NativeQueryBuilder::JOIN_TYPE_LEFT)
                ->join(
                    'emergency_reason_lookup',
                    'emrl',
                    'emrl.id = mt.emergency_reason_lookup_id',
                    NativeQueryBuilder::JOIN_TYPE_LEFT
                )
                ->join(
                    'comment', 'emcm', 'emcm.id = mt.emergency_reason_comment_id', NativeQueryBuilder::JOIN_TYPE_LEFT
                )
                ->join('person', 'emp', 'emp.id = mt.created_by');
        }

        $sql = $this->getEntityManager()->getConnection()->prepare($qb->getSql());
        $qb->bindParametersToStatement($sql);
        $sql->execute();

        return $sql->fetchAll();
    }

    public function getMotTestLogsResultCount(MotTestSearchParam $searchParam)
    {
        $qb = $this->prepareMotTest($searchParam)
            ->resetPart('select')
            ->select('count(mt.id) AS count')
            ->resetPart('orderBy')
            ->resetPart('offset')
            ->resetPart('limit');

        $sql = $this->getEntityManager()->getConnection()->prepare($qb->getSql());
        $qb->bindParametersToStatement($sql);

        $sql->execute();

        return $sql->fetch();
    }

    /**
     * NOT FINISHED
     * @param MotTestSearchParam $searchParam
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function prepareMotSearch(MotTestSearchParam $searchParam)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('test')
            ->from(MotTest::class, 'test')
            ->leftJoin(Site::class, 'site', 'WITH', 'site.id = test.vehicleTestingStation')
            ->innerJoin(Vehicle::class, 'vehicle', 'WITH', 'vehicle.id = test.vehicle')
            ->leftJoin(Make::class, 'make', 'WITH', 'make.id = test.make')
            ->leftJoin(Model::class, 'model', 'WITH', 'model.id = test.model')
            ->innerJoin(MotTestType::class, 'testType', 'WITH', 'test.motTestType = testType.id')
            ->innerJoin(Person::class, 'tester', 'WITH', 'tester.id = test.tester')
            ->andWhere("testType.code IN (:testTypes)")
            ->setParameter("testTypes", $this->testTypes);

        if ($searchParam->getDateFrom()) {
            $qb->andwhere('test.startedDate >= :DATE_FROM')
                ->setParameter('DATE_FROM', $searchParam->getDateFrom());
        }
        if ($searchParam->getDateTo()) {
            $qb->andwhere('test.startedDate <= :DATE_TO')
                ->setParameter('DATE_TO', $searchParam->getDateTo()->add(new \DateInterval('P1M')));
        }

        if ($searchParam->getSiteNumber()) {
            $qb->andwhere('site.siteNumber = :SITE_NR')
                ->setParameter('SITE_NR', $searchParam->getSiteNumber());
        }

        if ($searchParam->getTesterId()) {
            $qb->andwhere('test.tester = :TESTER_ID')
                ->setParameter('TESTER_ID', $searchParam->getTesterId());
        }

        if ($searchParam->getRegistration()) {
            $qb->andwhere('test.registration = :VRM')
                ->setParameter('VRM', $searchParam->getRegistration());
        }

        if ($searchParam->getVin()) {
            $qb->andwhere('test.vin = :VIN')
                ->setParameter('VIN', $searchParam->getVin());
        }

        if ($searchParam->getVehicleId()) {
            $qb->andwhere('test.vehicle = :VEHICLE_ID')
                ->setParameter('VEHICLE_ID', $searchParam->getVehicleId());
        }

        return $qb;
    }

    public function getMotTestSearchResult(MotTestSearchParam $searchParam)
    {
        $dql = $this->prepareMotSearch($searchParam);

        $orderBy = $searchParam->getSortColumnNameDatabase();
        if (is_array($orderBy)) {
            foreach ($orderBy as $order) {
                $dql->addOrderBy($order, $searchParam->getSortDirection());
            }
        } else {
            $dql->orderBy($orderBy, $searchParam->getSortDirection());
        }
        if ($searchParam->getStart() > 0) {
            $dql->setFirstResult($searchParam->getStart());
        }

        if ($searchParam->getRowCount() > 0) {
            $dql->setMaxResults($searchParam->getRowCount());
        }

        $query = $dql->getQuery()
            ->setFetchMode(MotTest::class, 'make', ClassMetadata::FETCH_EAGER)
            ->setFetchMode(MotTest::class, 'model', ClassMetadata::FETCH_EAGER);
        return $query->getResult();
    }

    public function getMotTestSearchResultCount(MotTestSearchParam $searchParam)
    {
        $dql = $this->prepareMotSearch($searchParam);
        $dql->select('count(test)');

        return (int)$dql->getQuery()->getSingleScalarResult();
    }

    /**
     * Checks whether the supplied User ID is the same as the one held against
     * the specified MOT Test.
     *
     * @param $testerId
     * @param $motTestNumber
     *
     * @return bool
     */
    public function isTesterForMot($testerId, $motTestNumber)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('mt.number')
            ->from(MotTest::class, 'mt')
            ->andwhere('mt.tester = :tester')
            ->andWhere('mt.number = :motTestNumber')
            ->setMaxResults(1);

        $qb->setParameter('tester', $testerId);
        $qb->setParameter('motTestNumber', str_replace(' ', '', $motTestNumber));

        $result = $qb->getQuery()->getOneOrNullResult();

        return $result !== null;
    }
}
