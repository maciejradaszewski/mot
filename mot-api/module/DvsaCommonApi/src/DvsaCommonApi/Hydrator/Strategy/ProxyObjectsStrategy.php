<?php

namespace DvsaCommonApi\Hydrator\Strategy;

use Doctrine\ORM\Proxy\Proxy;
use DoctrineModule\Stdlib\Hydrator\Strategy\AbstractCollectionStrategy;

/**
 * Class ProxyObjectsStrategy
 */
class ProxyObjectsStrategy extends AbstractCollectionStrategy
{
    public function hydrate($value)
    {
        return $value;
    }

    public function extract($value)
    {
        return $value instanceof Proxy ? null : $value;
    }
}
