<?php

namespace DvsaEntities\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\DqlBuilder\SearchParam\SiteSearchParam;
use DvsaEntities\DqlBuilder\SiteSlotUsageParamDqlBuilder;
use DvsaEntities\DqlBuilder\SlotUsageParamDqlBuilder;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteBusinessRole;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use Doctrine\ORM\Query\Expr\Join;

/**
 * SiteRepository
 *
 * Custom Doctrine Repository for reusable DQL queries
 * @codeCoverageIgnore
 */
class SiteRepository extends AbstractMutableRepository
{
    use SearchRepositoryTrait;

    protected $classes = [];
    protected $statuses = [];
    protected $types = [];
    protected $vehicleClasses = [];

    const TYPE_CTC = 'CTC';
    const TYPE_VTS = 'VTS';
    const TYPE_VRO = 'VRO';
    const TYPE_GVTS = 'GVTS';
    const TYPE_AREA_OFFICE = 'AREA OFFICE';
    const TYPE_SERVICE_DESK = 'SERVICE DESK';
    const TYPE_WELCOMBE_HOUSE = 'WELCOMBE HOUSE';
    const TYPE_BERKELEY_HOUSE = 'BERKELEY HOUSE';
    const TYPE_COURSE_VENUE = 'COURSE VENUE';

    const STATUS_APPLIED = 'APPLIED';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_LAPSED = 'LAPSED';
    const STATUS_REJECTED = 'REJECTED';
    const STATUS_RETRACTED = 'RETRACTED';

    const VEHICLE_CLASS_1 = '1';
    const VEHICLE_CLASS_2 = '2';
    const VEHICLE_CLASS_3 = '3';
    const VEHICLE_CLASS_4 = '4';
    const VEHICLE_CLASS_5 = '5';
    const VEHICLE_CLASS_7 = '7';

    /**
     * Initializes a new <tt>EntityRepository</tt>.
     *
     * Fire up the class and add build the static arrays
     *
     * @param EntityManager $em The EntityManager to use.
     * @param ClassMetadata $class The class descriptor.
     */
    public function __construct($em, ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->makeTypesArray();
        $this->makeStatusesArray();
        $this->makeVehicleClassesArray();
    }

    /**
     * @param $id
     *
     * @return Site
     * @throws NotFoundException
     */
    public function get($id)
    {
        $site = $this->find($id);

        if ($site === null) {
            throw new NotFoundException("Site not found");
        }

        return $site;
    }

    /**
     * @param $site Site
     */
    public function save($site)
    {
        parent::save($site);
    }

    /**
     * @param $id
     *
     * @return Site
     * @throws NotFoundException
     */
    public function getVehicleTestingStation($id)
    {
        $site = $this->get($id);
        if (!$site->isVehicleTestingStation()) {
            throw new NotFoundException("Vehicle Testing Station not found");
        }

        return $site;
    }

    /**
     * Find vehicle testing stations that match a (partial) site number
     * @param string $partialSiteNumber
     * @param int $maxResults
     * @return ArrayCollection
     */
    public function findVehicleTestingStationsByPartialSiteNumber($partialSiteNumber, $maxResults = 100)
    {
        $dql = 'SELECT vts from DvsaEntities\Entity\Site vts '
            . 'WHERE vts.siteNumber LIKE :SITE_NUMBER '
            . 'ORDER BY vts.id ASC';
        $query = $this->_em->createQuery($dql)->setMaxResults($maxResults);
        $query->setParameter('SITE_NUMBER', $partialSiteNumber . '%');

        return $query->getResult();
    }

    /**
     * @param $siteNumber
     *
     * @return Site
     * @throws \DvsaCommonApi\Service\Exception\NotFoundException
     */
    public function getBySiteNumber($siteNumber)
    {
        $result = $this->findBy(['siteNumber' => $siteNumber]);
        if (empty($result)) {
            throw new NotFoundException('VehicleTestingStation', $siteNumber);
        }

        return $result[0];
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @return array
     */
    public function getVehicleClasses()
    {
        return $this->vehicleClasses;
    }

    protected function makeTypesArray()
    {
        $types = [
            self::TYPE_CTC,
            self::TYPE_VTS,
            self::TYPE_VRO,
            self::TYPE_GVTS,
            self::TYPE_AREA_OFFICE,
            self::TYPE_SERVICE_DESK,
            self::TYPE_WELCOMBE_HOUSE,
            self::TYPE_BERKELEY_HOUSE,
            self::TYPE_COURSE_VENUE
        ];

        foreach ($types as $type) {
            $this->types[strtolower(str_replace(" ", "_", $type))] = $type;
        }
    }

    protected function makeStatusesArray()
    {
        $statuses = [
            self::STATUS_APPLIED,
            self::STATUS_APPROVED,
            self::STATUS_LAPSED,
            self::STATUS_REJECTED,
            self::STATUS_RETRACTED
        ];

        foreach ($statuses as $status) {
            $this->statuses[strtolower($status)] = $status;
        }
    }

    protected function makeVehicleClassesArray()
    {
        $vehicleClasses = [
            self::VEHICLE_CLASS_1,
            self::VEHICLE_CLASS_2,
            self::VEHICLE_CLASS_3,
            self::VEHICLE_CLASS_4,
            self::VEHICLE_CLASS_5,
            self::VEHICLE_CLASS_7,
        ];

        foreach ($vehicleClasses as $vehicleClass) {
            $this->vehicleClasses[strtolower($vehicleClass)] = $vehicleClass;
        }
    }

    protected function getSqlBuilder($params)
    {
        return new SlotUsageParamDqlBuilder(
            $this->getEntityManager(),
            $params
        );
    }

    private function getSiteSqlBuilder($params)
    {
        return new SiteSlotUsageParamDqlBuilder(
            $this->getEntityManager(),
            $params
        );
    }

    /**
     * @param int $orgId
     * @param string $start
     * @param string $end
     * @return int
     */
    public function getSlotUsage($orgId, $start, $end)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('COUNT(t.id) total_usage')
            ->from($this->getEntityName(), 's')
            ->join(\DvsaEntities\Entity\MotTest::class, 't', Query\Expr\Join::INNER_JOIN, 's.id = t.vehicleTestingStation')
            ->join(\DvsaEntities\Entity\MotTestType::class, 'tt', Query\Expr\Join::INNER_JOIN, 't.motTestType = tt.id')
            ->join(\DvsaEntities\Entity\MotTestStatus::class, 'ts', Query\Expr\Join::INNER_JOIN, 't.status = ts.id')
            ->where('ts.name = :STATUS')
            ->andWhere('s.organisation = :ORG_ID')
            ->andWhere('t.completedDate >= :DATE_FROM')
            ->andWhere('t.completedDate <= :DATE_TO')
            ->andWhere('tt.isSlotConsuming = 1')
            ->setParameter("STATUS", MotTestStatusName::PASSED)
            ->setParameter('ORG_ID', $orgId)
            ->setParameter('DATE_FROM', $start)
            ->setParameter('DATE_TO', $end);

        $result = $queryBuilder->getQuery()->getResult();

        return (int)$result[0]['total_usage'];
    }

    /**
     * @param int $siteId
     * @param string $start
     * @param string $end
     * @return int
     */
    public function getVtsSlotUsage($siteId, $start, $end)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('COUNT(t.id) total_usage')
            ->from(\DvsaEntities\Entity\MotTest::class, 't')
            ->join(\DvsaEntities\Entity\MotTestType::class, 'tt', Query\Expr\Join::INNER_JOIN, 't.motTestType = tt.id')
            ->join(\DvsaEntities\Entity\MotTestStatus::class, 'ts', Query\Expr\Join::INNER_JOIN, 't.status = ts.id')
            ->where('ts.name = :STATUS')
            ->andWhere('t.vehicleTestingStation = :SITE_ID')
            ->andWhere('t.completedDate >= :DATE_FROM')
            ->andWhere('t.completedDate <= :DATE_TO')
            ->andWhere("tt.isSlotConsuming = 1")
            ->setParameter("STATUS", MotTestStatusName::PASSED)
            ->setParameter('SITE_ID', $siteId)
            ->setParameter('DATE_FROM', $start)
            ->setParameter('DATE_TO', $end);

        $result = $queryBuilder->getQuery()->getResult();

        return (int)$result[0]['total_usage'];
    }

    public function searchOrgSlotUsage($params, $format)
    {
        $result = $this->search($params, $format);

        $sqlBuilder = $this->getSqlBuilder($params)->generate();
        $totalResultCount = $sqlBuilder->getSearchCountQuery()->getResult();
        $totalSlotUsage = isset($totalResultCount[0][2]) ? $totalResultCount[0][2] : 0;

        return array_merge($result, ['totalSlotUsage' => $totalSlotUsage]);
    }

    public function searchSiteSlotUsage($params, $format)
    {
        $sqlBuilder = $this->getSiteSqlBuilder($params)->generate();

        $this->searchDql = $sqlBuilder->getSearchDql();

        $this->searchCountDql = $sqlBuilder->getSearchCountDql();

        $totalResultCount = $sqlBuilder->getSearchCountQuery()->getResult();

        $results = $sqlBuilder->getSearchQuery()->getResult();

        $formattedResults = $format->extractItems($results);

        return [
            "resultCount" => (string)count($results),
            "totalResultCount" => isset($totalResultCount[0][1]) ? $totalResultCount[0][1] : 0,
            "data" => $formattedResults,
            "searched" => $params->toArray()
        ];
    }

    public function findForPersonWithRole(Person $person, SiteBusinessRole $role, $status = null)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('s')
            ->from(SiteBusinessRoleMap::class, 'sbrm')
            ->join(Site::class, 's', Join::INNER_JOIN, 'sbrm.site = s.id')
            ->where('sbrm.person = :person')
            ->andWhere('sbrm.siteBusinessRole = :role')
            ->setParameter('person', $person)
            ->setParameter('role', $role);

        if ($status) {
            $queryBuilder
                ->join(BusinessRoleStatus::class, 'brs', Join::INNER_JOIN, 'sbrm.businessRoleStatus = brs.id')
                ->andWhere('brs.code = :status')
                ->setParameter('status', $status);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Return the total result of the search for sites
     *
     * @param SiteSearchParam $searchParam
     * @return int
     */
    public function findSitesCount(SiteSearchParam $searchParam)
    {
        try {
            return $this->buildFindSites($searchParam, true)->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
    }

    /**
     * Return an array containing the list of site related to a search
     *
     * @param SiteSearchParam $searchParam
     * @return array
     */
    public function findSites(SiteSearchParam $searchParam)
    {
        return $this->buildFindSites($searchParam)->getResult(AbstractQuery::HYDRATE_SCALAR);
    }

    /**
     * Build the Query to search for a site
     *
     * @param SiteSearchParam $searchParam
     * @param bool $isCount
     * @return AbstractQuery|static
     */
    public function buildFindSites(SiteSearchParam $searchParam, $isCount = false)
    {
        $isFullTextSiteNameNumber = (!empty($searchParam->getSiteNumber()) || !empty($searchParam->getSiteName()))
            && !empty($this->buildFullTextSearchSites($searchParam->getSiteNumber(), $searchParam->getSiteName()));

        $isFullTextSiteTownPostcode = (!empty($searchParam->getSiteTown()) || !empty($searchParam->getSitePostcode()))
            && !empty($this->buildFullTextSearchSites($searchParam->getSiteTown(), $searchParam->getSitePostcode()));

        $isSiteVehicleClass = empty($searchParam->getSiteVehicleClass()) === false;

        $sql = $this->buildFindSitesSql(
            $isCount,
            $isFullTextSiteNameNumber,
            $isFullTextSiteTownPostcode,
            $isSiteVehicleClass
        );

        if ($isSiteVehicleClass === true) {
            $sql .= " HAVING COUNT(DISTINCT vc.code) = :NUMBER_SITE_VEHICLE_CLASS";
        }

        // Temporary limit for 1.10
        $sql .= " LIMIT 100";

        $query = $this->_em
            ->createNativeQuery($sql, $this->getResultSetMappingFindSites($isCount))
            ->setParameter(':BUSINESS', 'BUS');

        if ($isFullTextSiteNameNumber === true) {
            $query->setParameter(
                ':SITE_NAME_NUMBER',
                $this->buildFullTextSearchSites($searchParam->getSiteNumber(), $searchParam->getSiteName())
            );
        }
        if ($isFullTextSiteTownPostcode === true) {
            $query->setParameter(
                ':SITE_TOWN_POSTCODE',
                $this->buildFullTextSearchSites($searchParam->getSiteTown(), $searchParam->getSitePostcode())
            );
        }
        if ($isSiteVehicleClass === true) {
            $query->setParameter(':SITE_VEHICLE_CLASS', $searchParam->getSiteVehicleClass());
            $query->setParameter(':NUMBER_SITE_VEHICLE_CLASS', count($searchParam->getSiteVehicleClass()));
        }

        return $query;
    }

    /**
     * Build the native SQL to search for a site
     *
     * @param $isCount
     * @param $isFullTextSiteNameNumber
     * @param $isFullTextSiteTownPostcode
     * @param $isSiteVehicleClass
     * @return string
     */
    private function buildFindSitesSql(
        $isCount,
        $isFullTextSiteNameNumber,
        $isFullTextSiteTownPostcode,
        $isSiteVehicleClass
    ) {
        $select = ($isCount === true ? 'COUNT(DISTINCT site.id) AS matchSite'
            : '
            site.id,
            site.id as siteId,
            site.site_number,
            site.name,
            a.town,
            a.postcode,
            (SELECT GROUP_CONCAT(DISTINCT vc.code ORDER BY vc.code ASC SEPARATOR \',\')
            FROM site
                LEFT JOIN auth_for_testing_mot_at_site site_auth ON (site.id = site_auth.site_id)
                LEFT JOIN vehicle_class vc ON site_auth.vehicle_class_id = vc.id
            WHERE site.id = siteId
            GROUP BY
                site.id,
                site.site_number,
                site.name,
                a.town,
                a.postcode
            LIMIT 1) AS roles'
        );

        $sql = "SELECT $select
            FROM site
                LEFT JOIN site_contact_detail_map scdm ON (site.id = scdm.site_id)
                LEFT JOIN contact_detail cd ON (scdm.contact_detail_id = cd.id)
                LEFT JOIN address a ON (cd.address_id = a.id)
                JOIN site_contact_type sct ON (scdm.site_contact_type_id = sct.id)
                LEFT JOIN auth_for_testing_mot_at_site site_auth ON (site.id = site_auth.site_id)
                LEFT JOIN vehicle_class vc ON site_auth.vehicle_class_id = vc.id
            WHERE sct.code = :BUSINESS";

        if ($isFullTextSiteNameNumber === true) {
            $sql .= ' AND (MATCH(site.site_number, site.name) AGAINST (:SITE_NAME_NUMBER IN BOOLEAN MODE))';
        }
        if ($isFullTextSiteTownPostcode === true) {
            $sql .= ' AND (MATCH(a.town, a.postcode) AGAINST (:SITE_TOWN_POSTCODE IN BOOLEAN MODE))';
        }

        if ($isSiteVehicleClass === true) {
            $sql .= " AND vc.code IN (:SITE_VEHICLE_CLASS)";
        }

        if ($isCount !== true) {
            $sql .= '
                GROUP BY
                site.id,
                site.site_number,
                site.name,
                a.town,
                a.postcode';
        }

        return $sql;
    }

    /**
     * Build the result set map of a search for sites
     *
     * @param $isCount
     * @return Query\ResultSetMapping
     */
    private function getResultSetMappingFindSites($isCount)
    {
        $rsm = new Query\ResultSetMapping();
        if ($isCount) {
            $rsm->addScalarResult('matchSite', 'matchSite');
            return $rsm;
        }
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('site_number', 'site_number');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('town', 'town');
        $rsm->addScalarResult('postcode', 'postcode');
        $rsm->addScalarResult('roles', 'roles');

        return $rsm;
    }

    /**
     * Build the full text search for the search of sites
     *
     * @param $param1
     * @param $param2
     * @return string
     */
    private function buildFullTextSearchSites($param1, $param2)
    {
        $words = array_merge(explode(' ', mb_strtolower($param1)), explode(' ', mb_strtolower($param2)));

        $finalClause = '';
        foreach ($words as $word) {
            if (empty($word) === false && strlen($word) > 2) {
                $finalClause .= ' +' . $word . '*';
            }
        }

        return $finalClause;
    }
}
