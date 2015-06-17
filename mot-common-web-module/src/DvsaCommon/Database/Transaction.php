<?php

namespace DvsaCommon\Database;

use Doctrine\ORM\EntityManager;

/**
 * Wraps around entity manager and provides only functions related to handling transaction
 */
class Transaction
{
    private $entityManager;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    public function begin()
    {
        $this->entityManager->beginTransaction();
    }

    public function rollback()
    {
        $this->entityManager->rollback();
    }

    public function commit()
    {
        $this->entityManager->commit();
    }

    public function flush()
    {
        $this->entityManager->flush();
    }
}
