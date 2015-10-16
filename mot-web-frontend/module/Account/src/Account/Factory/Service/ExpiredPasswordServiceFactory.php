<?php

namespace Account\Factory\Service;

use Account\Service\ExpiredPasswordService;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaClient\Mapper\ExpiredPasswordMapper;
use DvsaCommon\Configuration\MotConfig;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExpiredPasswordServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $openAMClientOptions = $serviceLocator->get(OpenAMClientOptions::class);
        $realm = $openAMClientOptions->getRealm();

        return new ExpiredPasswordService(
            $serviceLocator->get('MotIdentityProvider'),
            $serviceLocator->get(MotConfig::class),
            $serviceLocator->get(ExpiredPasswordMapper::class),
            $serviceLocator->get(OpenAMClientInterface::class),
            $realm
        );
    }
}
