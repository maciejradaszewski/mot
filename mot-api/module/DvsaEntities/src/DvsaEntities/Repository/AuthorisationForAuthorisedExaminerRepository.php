<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
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

    public function getAuthorisedExaminerData($username)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('username', 'username');
        $rsm->addScalarResult('slots', 'slots');
        $rsm->addScalarResult('slots_in_use', 'slotsInUse');

        /*
         * Should really use ORM here but it would require
         * adding inverse relationships to both VTS, AE, and MOT
         */
        $authorisedExaminer = $this->getEntityManager()
            ->createNativeQuery(
                "select
                    ae.id,
                    p.username,
                    count(mt.id) as slots_in_use
                from
                    mot.authorisation_for_authorised_examiner ae
                    join mot.organisation o on ae.organisation_id = o.id
                    join mot.organisation_business_role_map obrm on o.id = obrm.organisation_id
                    join mot.person p on obrm.person_id = p.id
                    left outer join mot.site vts on o.id = vts.organisation_id
                    left outer join mot.mot_test mt on vts.id = mt.site_id and mt.status = :STATUS_ACTIVE
                    left outer join mot_test_type mtt on mt.mot_test_type_id = mtt.id
                        and mtt.code not in (
                        :ROUTINE_DEMONSTRATION_TEST,
                        :DEMONSTRATION_TEST_FOLLOWING_TRAINING
                        )
                WHERE
                    p.username = :USERNAME
                GROUP BY
                    ae.id,
                    p.username
                LIMIT 1",
                $rsm
            )
            ->setParameter('USERNAME', $username)
            ->setParameter('STATUS_ACTIVE', MotTestStatusName::ACTIVE)
            ->setParameter('ROUTINE_DEMONSTRATION_TEST', MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST)
            ->setParameter(
                'DEMONSTRATION_TEST_FOLLOWING_TRAINING',
                MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING
            )
            ->getResult();

        return current($authorisedExaminer);
    }
}
