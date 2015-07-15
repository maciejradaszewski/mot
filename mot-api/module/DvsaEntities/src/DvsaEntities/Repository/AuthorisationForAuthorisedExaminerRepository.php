<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\Person;

/**
 * Repository for {@link \DvsaEntities\Entity\AuthorisationForAuthorisedExaminer}.
 * @codeCoverageIgnore
 */
class AuthorisationForAuthorisedExaminerRepository extends AbstractMutableRepository
{
    const SEQ_CODE = 'AEREF';

    const ERR_AEREF_NOT_FOUND = "Next reference number of Authorised Examiner was not found";

    public function getNextAeRef()
    {
        $sql = 'call sp_sequence(:CODE);';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->bindValue('CODE', self::SEQ_CODE);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($stmt->rowCount() === 0 || !isset($result['sequence'])) {
            throw new \Exception(self::ERR_AEREF_NOT_FOUND);
        }

        return $result['sequence'];
    }

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
  brs.code = :busRoleCode
    AND
  p.id = :personId;
";
        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter('busRoleCode', BusinessRoleStatusCode::ACTIVE);
        $query->setParameter('personId', $person->getId());

        return $query->getResult();
    }
}
