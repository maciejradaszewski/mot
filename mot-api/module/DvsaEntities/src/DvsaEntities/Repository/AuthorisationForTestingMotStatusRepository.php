<?php

namespace DvsaEntities\Repository;

use DvsaEntities\Entity\AuthorisationForTestingMotStatus;

/**
 * Class AuthorisationForTestingMotStatusRepository
 *
 * @package DvsaEntities\Repository
 * @codeCoverageIgnore
 */
class AuthorisationForTestingMotStatusRepository extends EnumType1Repository
{
    /**
     * @param string $code
     *
     * @return AuthorisationForTestingMotStatus
     */
    public function getByCode($code)
    {
        return parent::getByCode($code);
    }
}
