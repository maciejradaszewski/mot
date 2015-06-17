<?php

namespace UserFacade;

use Doctrine\ORM\EntityManager;

/**
 * Class UserFacadeLocal
 *
 * @package UserFacade
 */
class UserFacadeLocal implements UserFacadeInterface
{

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
