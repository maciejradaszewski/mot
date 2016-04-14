<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\DqlBuilder\SearchParam\DemoTestRequestsSearchParam;
use DvsaEntities\Entity\QualificationAward;

class QualificationAwardRepository extends AbstractMutableRepository
{
    const DEMO_TEST_NEEDED = 'Demo Test Needed';
    const CONTACT_TYPE_PERSONAL = 'PERSONAL';
    /**
     * @param $personId
     * @return QualificationAward[]
     */
    public function findAllByPersonId($personId)
    {
        $query = $this->getDefaultQueryBuilder($personId)->getQuery();

        return $query->getResult();
    }

    /**
     * @param string $vehicleClassGroupCode
     * @param int $personId
     * @return QualificationAward
     * @throws NotFoundException
     */
    public function getOneByGroupAndPersonId($vehicleClassGroupCode, $personId)
    {
        $query = $this
            ->getDefaultQueryBuilder($personId)
            ->andWhere("vcg.code = :code")
            ->setParameter("code", $vehicleClassGroupCode)
            ->getQuery();

        $result = $query->getOneOrNullResult();

        if ($result === null) {
            throw new NotFoundException($this->getEntityName(), $vehicleClassGroupCode);
        }

        return $result;
    }

    /**
     * @param string $vehicleClassGroupCode
     * @param int $personId
     * @return QualificationAward
     */
    public function findOneByGroupAndPersonId($vehicleClassGroupCode, $personId)
    {
        $query = $this
            ->getDefaultQueryBuilder($personId)
            ->andWhere("vcg.code = :code")
            ->setParameter("code", $vehicleClassGroupCode)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    private function getDefaultQueryBuilder($personId)
    {
        return $this
            ->createQueryBuilder("mtc")
            ->addSelect(["p", "vcg", "s"])
            ->leftJoin("mtc.site", "s")
            ->innerJoin("mtc.person", "p")
            ->innerJoin("mtc.vehicleClassGroup", "vcg")
            ->where("p.id = :personId")
            ->setParameter("personId", $personId);
    }

    /**
     * @param DemoTestRequestsSearchParam $searchParam
     * @return int
     */
    public function findAllDemoTestRequestsUsersCount(DemoTestRequestsSearchParam $searchParam)
    {
        try {
            return count($this->findAllDemoTestRequestsUsers(true, $searchParam)->getResult(AbstractQuery::HYDRATE_SCALAR));
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 0;
        }
    }

    /**
     * Return an array containing the list of site related to a search
     *
     * @param DemoTestRequestsSearchParam $searchParam
     * @return array[]
     */
    public function findAllDemoTestRequestsUsersSorted(DemoTestRequestsSearchParam $searchParam)
    {
        return $this->findAllDemoTestRequestsUsers(false, $searchParam)->getResult(AbstractQuery::HYDRATE_SCALAR);
    }

    private function findAllDemoTestRequestsUsers($isCount, DemoTestRequestsSearchParam $searchParam)
    {
        $select = ($isCount === true ? 'qa.id'
            : '
            qa.id,
            pe.username,
            p.number,
            e.email,
            pe.firstName,
            pe.middleName,
            pe.familyName,
            vcg.code,
            s.siteNumber,
            a.postcode,
            qa.createdOn'
        );
        $queryBuilder = $this->createQueryBuilder('qa');
        $queryBuilder
            ->distinct(true)
            ->select($select)
            ->leftJoin("qa.site", "s")
            ->leftJoin("qa.vehicleClassGroup","vcg")
            ->leftJoin("s.contacts","scdm")
            ->leftJoin("scdm.contactDetail","cds")
            ->leftJoin("cds.address","a")
            ->leftJoin("qa.person","pe")
            ->leftJoin("pe.contacts","pecdm")
            ->leftJoin("pecdm.contactDetail","cd")
            ->leftJoin("pecdm.type","pt")
            ->leftJoin("cd.emails","e")
            ->leftJoin("cd.phones","p")
            ->leftJoin("pe.authorisationsForTestingMot","aftm")
            ->leftJoin("aftm.vehicleClass","vc")
            ->leftJoin("aftm.status","ast")
            ->where('pt.name = :contactTypePersonal')
            ->andWhere('ast.name = :demoTestNeeded')
            ->andWhere('qa.vehicleClassGroup = vc.group')
            ->setParameters(
                [
                    'contactTypePersonal' => self::CONTACT_TYPE_PERSONAL,
                    'demoTestNeeded'      => self::DEMO_TEST_NEEDED,
                ]
            );

        if($isCount === false)
        {
            foreach ($searchParam->getSortColumnNameDatabase() as $col) {
                $queryBuilder->orderBy($col, $searchParam->getSortDirection());
            }
            $queryBuilder->setFirstResult($searchParam->getStart());
            if($searchParam->getRowCount() != 0){
                $queryBuilder->setMaxResults($searchParam->getRowCount());
            }
        }


        return $queryBuilder->getQuery();
    }
}
