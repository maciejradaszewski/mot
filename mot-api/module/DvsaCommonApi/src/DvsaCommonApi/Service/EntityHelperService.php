<?php

namespace DvsaCommonApi\Service;

use Doctrine\ORM\EntityManager;

/**
 * Class EntityHelperService.
 */
class EntityHelperService
{
    use EntityFinderTrait;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
