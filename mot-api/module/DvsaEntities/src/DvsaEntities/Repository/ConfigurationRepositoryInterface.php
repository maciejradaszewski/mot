<?php

namespace DvsaEntities\Repository;

/**
 * Interface ConfigurationRepositoryInterface
 *
 * @package DvsaEntities\Repository
 */
interface ConfigurationRepositoryInterface
{
    /**
     * @param string $paramKey
     *
     * @return mixed
     */
    public function getValue($paramKey);
}
