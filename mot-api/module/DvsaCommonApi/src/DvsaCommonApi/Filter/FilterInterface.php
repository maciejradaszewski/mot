<?php

namespace DvsaCommonApi\Filter;

use Zend\Filter\FilterInterface as ZendFilterInterface;

/**
 * Interface FilterInterface.
 */
interface FilterInterface extends ZendFilterInterface
{
    /**
     * @param array $values
     *
     * @return mixed
     */
    public function filterMultiple(array $values);
}
