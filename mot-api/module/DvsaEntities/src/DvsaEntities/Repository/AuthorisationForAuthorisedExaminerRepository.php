<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;

/**
 * Repository for {@link \DvsaEntities\Entity\AuthorisationForAuthorisedExaminer}.
 */
class AuthorisationForAuthorisedExaminerRepository extends AbstractMutableRepository
{
    public function getBySitePositionForPerson(Person $person)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(AuthorisationForAuthorisedExaminer::class, 'afa');
        $sql = "
        SELECT afa.* FROM
auth_for_ae afa
  JOIN organisation o ON (afa.organisation_id = o.id)
  JOIN site s ON (s.organisation_id = o.id)
  JOIN site_business_role_map sbrm ON (sbrm.site_id = s.id)
  JOIN business_role_status brs ON (sbrm.status_id = brs.id)
  JOIN person p ON (sbrm.person_id = p.id)
WHERE
  brs.code = 'AC'
    AND
  p.id = :personId;
";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('personId', $person->getId());
        $aes = $query->getResult();

        return $aes;
    }
}
