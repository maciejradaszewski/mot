<?php

namespace Application\Listener\Factory;

use Application\Listener\ExpiredPasswordListener;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaCommon\Date\DateTimeHolder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExpiredPasswordListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $openAMClientOptions = $serviceLocator->get(OpenAMClientOptions::class);
        $realm = $openAMClientOptions->getRealm();

        $gracePeriod = $config['password_expiry_grace_period'];
        return new ExpiredPasswordListener(
            $serviceLocator->get('MotIdentityProvider'),
            $serviceLocator->get(OpenAMClientInterface::class),
            new DateTimeHolder(),
            $serviceLocator->get('Application\Logger'),
            $realm,
            $gracePeriod
        );
    }
}
