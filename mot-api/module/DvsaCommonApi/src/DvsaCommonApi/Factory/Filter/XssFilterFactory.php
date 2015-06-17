<?php

namespace DvsaCommonApi\Factory\Filter;

use DvsaCommonApi\Filter\XssFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create XssFilter.
 */
class XssFilterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return XssFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $htmlPurifier = $serviceLocator->get('HTMLPurifier');

        return new XssFilter($htmlPurifier);
    }
}
