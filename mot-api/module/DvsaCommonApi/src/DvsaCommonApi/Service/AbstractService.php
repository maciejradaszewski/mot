<?php

namespace DvsaCommonApi\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Service\Exception\NotFoundException;

/**
 * Abstract for all services that require EntityManager
 */
abstract class AbstractService
{
    use EntityFinderTrait;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
