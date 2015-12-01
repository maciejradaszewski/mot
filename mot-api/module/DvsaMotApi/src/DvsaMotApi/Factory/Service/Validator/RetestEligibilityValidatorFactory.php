<?php

namespace DvsaMotApi\Factory\Service\Validator;

use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UserApi\SpecialNotice\Service\SpecialNoticeService;

class RetestEligibilityValidatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RetestEligibilityValidator(
            $serviceLocator->get('NonWorkingDaysHelper'),
            $serviceLocator->get('MotTestRepository')
        );
    }
}
