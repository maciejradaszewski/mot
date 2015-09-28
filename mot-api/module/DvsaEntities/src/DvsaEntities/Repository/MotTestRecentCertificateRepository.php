<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityRepository;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\MotTestRecentCertificate;
use DvsaMotApi\Service\Paginator;

/**
 * Retrieve list of PDF MOT Certificates fot a Vehicle testing station
 */
class MotTestRecentCertificateRepository extends AbstractMutableRepository
{
    /**
     * @param int $vtsId
     * @param int $firstResult
     * @param int $maxResult
     * @return mixed
     */
    public function findByVtsId($vtsId, $firstResult, $maxResult)
    {
        $qb = $this
            ->getQB($vtsId)
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResult)
            ->orderBy("cert.createdOn", "DESC");

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $vtsId
     * @return int
     */
    public function getTotalCertsCountInVts($vtsId)
    {
        $qb = $this->getQB($vtsId);
        return $qb
            ->select("count(cert.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $vtsId
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getQB($vtsId)
    {
        return $this
            ->createQueryBuilder("cert")
            ->addSelect(["model", "make"])
            ->innerJoin("cert.status", " status")
            ->leftJoin("cert.make", "make")
            ->leftJoin("cert.model", "model")
            ->where("cert.vtsId = :siteId")
            ->setParameter("siteId", $vtsId);
    }

    /**
     * @param $id
     * @return MotTestRecentCertificate
     * @throws NotFoundException
     */
    public function getById($id)
    {
        $recentCert = $this->find($id);

        if (is_null($recentCert) || (!$recentCert instanceof MotTestRecentCertificate)) {
            throw new NotFoundException("Recent certificate", $id);
        }

        return $recentCert;
    }
}
