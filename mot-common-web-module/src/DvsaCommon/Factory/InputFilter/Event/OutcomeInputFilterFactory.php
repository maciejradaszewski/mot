<?php

namespace DvsaCommon\Factory\InputFilter\Event;

use DvsaCommon\InputFilter\Event\OutcomeInputFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OutcomeInputFilterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return OutcomeInputFilter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $inputFilter = new OutcomeInputFilter();
        $inputFilter->init();

        return $inputFilter;
    }
}