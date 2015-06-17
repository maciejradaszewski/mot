<?php

namespace DvsaEntities\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class AuthorisationForTestingMotStatusRepository
 *
 * @package DvsaEntities\Repository
 * @codeCoverageIgnore
 */
class AuthorisationForTestingMotStatusRepository extends EntityRepository
{
    use EnumType1RepositoryTrait;
}
