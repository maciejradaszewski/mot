<?php

namespace DvsaMotApi\Factory\Service\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\Digits;
use DvsaMotApi\Service\Validator\DemoTestAssessmentValidator;

class DemoTestAssessmentValidatorFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DemoTestAssessmentValidator(
            new Digits()
        );
    }
}
