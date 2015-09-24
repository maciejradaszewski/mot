<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityRepository;

class PermissionToAssignRoleMapRepository extends EntityRepository
{
    /**
     * @param string $roleCode
     * @return string
     * @throws NotFoundException
     */
    public function getPermissionCodeByRoleCode($roleCode)
    {

        $qb = $this->createQueryBuilder('ptarm')
            ->select('p.code')
            ->innerJoin("ptarm.permission", "p")
            ->innerJoin('ptarm.role', 'r')
            ->where('r.code = :roleCode')
            ->setParameter('roleCode', $roleCode);

        $permissionCode = $qb->getQuery()->getSingleScalarResult();

        if (!$permissionCode) {
            throw new NotFoundException('Role code ' . $roleCode . ' not found');
        }

        return $permissionCode;
    }
}