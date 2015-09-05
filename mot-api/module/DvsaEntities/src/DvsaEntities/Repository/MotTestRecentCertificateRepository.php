<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityRepository;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\MotTestRecentCertificate;

/**
 * Retrieve list of PDF MOT Certificates fot a Vehicle testing station
 */
class MotTestRecentCertificateRepository extends AbstractMutableRepository
{
    /**
     * @param int $vtsId
     * @return array
     */
    public function findByVtsId($vtsId)
    {
        $query =
            $this->getEntityManager()->createQuery(
                'SELECT cert
              FROM DvsaEntities\Entity\MotTestRecentCertificate cert
                  JOIN cert.status status
                  LEFT JOIN cert.make make
                  LEFT JOIN cert.model model
              WHERE cert.vtsId = :siteId
              ORDER BY cert.createdOn DESC'
            )->setParameter('siteId', $vtsId);

        return $query->getResult();
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
