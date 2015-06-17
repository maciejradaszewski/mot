<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityManager;

/**
 * Class CensorPhraseRepository
 * @package DvsaEntities\Repository
 * @codeCoverageIgnore
 */
class CensorPhraseRepository
{

    const BAD_PHRASES_LIST_QUERY = "SELECT phrase FROM censor_blacklist";

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->_em = $entityManager;
    }

    /**
     * @return array
     */
    public function getBlacklist()
    {
        $conn = $this->_em->getConnection();
        $stmt = $conn->query(self::BAD_PHRASES_LIST_QUERY);
        $phrases = [];
        while ($row = $stmt->fetch()) {
            $phrases[] = $row['phrase'];
        }
        $stmt->closeCursor();
        $conn->close();

        return $phrases;
    }
}
