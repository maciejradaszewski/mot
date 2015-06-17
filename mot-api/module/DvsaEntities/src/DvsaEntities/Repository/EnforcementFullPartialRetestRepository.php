<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityManagerInterface;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaEntities\Entity\EnforcementFullPartialRetest;

class EnforcementFullPartialRetestRepository extends AbstractMutableRepository
{
    /**
     * @param int $id
     *
     * @return EnforcementFullPartialRetest
     * @throws NotFoundException
     */
    public function get($id)
    {
        $entity = $this->find(intval($id));
        if ($entity === null) {
            throw new NotFoundException("FullPartialRetest lookup", $id);
        }
        return $entity;
    }
}
