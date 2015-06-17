<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityRepository;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaEntities\Entity\PersonSystemRoleMap;

/**
 * Repository for {@link \DvsaEntities\Entity\PersonSystemRoleMap}.
 */
class PersonSystemRoleMapRepository extends EntityRepository
{
    /**
     * @param $personId
     * @return PersonSystemRoleMap[]
     */
    public function getActiveUserRoles($personId)
    {
        $qb = $this
            ->createQueryBuilder("srbm")
            ->innerJoin("srbm.person", "p")
            ->innerJoin("srbm.businessRoleStatus", "st")
            ->innerJoin("srbm.personSystemRole", "sr")
            ->innerJoin("srbm.businessRoleStatus", "rs")
            ->where("p.id = :personId")
            ->andWhere("st.code in (:statusCode)")
            ->setParameter("personId", $personId)
            ->setParameter("statusCode", BusinessRoleStatusCode::ACTIVE);

        $roles = $qb->getQuery()->getResult();

        return $roles;
    }
}
