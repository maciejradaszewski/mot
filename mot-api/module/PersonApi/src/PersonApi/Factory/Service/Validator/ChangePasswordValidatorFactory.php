<?php

namespace PersonApi\Factory\Service\Validator;

use PersonApi\Service\Validator\ChangePasswordValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommon\InputFilter\Account\ChangePasswordInputFilter;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use Dvsa\OpenAM\OpenAMClientInterface;

class ChangePasswordValidatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $identityProvider = $serviceLocator->get(MotIdentityProviderInterface::class);
        $inputFilter = new ChangePasswordInputFilter($identityProvider);
        $inputFilter->init();

        return new ChangePasswordValidator(
            $identityProvider,
            $inputFilter,
            $serviceLocator->get(OpenAMClientInterface::class),
            $serviceLocator->get(OpenAMClientOptions::class)->getRealm()
        );
    }
}
