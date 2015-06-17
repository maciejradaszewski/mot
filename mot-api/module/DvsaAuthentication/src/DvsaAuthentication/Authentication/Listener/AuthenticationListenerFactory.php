<?php

namespace DvsaAuthentication\Authentication\Listener;

use Zend\Log\LoggerInterface;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\Adapter\AbstractAdapter;


class AuthenticationListenerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $sl
     * @return ApiAuthenticationListener
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        /** @var \Zend\Authentication\AuthenticationService $auth */
        $auth = $sl->get('DvsaAuthenticationService');

        /** @var LoggerInterface $logger */
        $logger = $sl->get('Application\Logger');

        /** @var array $config */
        $config = $sl->get('config');

        $whitelist = $config['dvsa_authentication']['whitelist'];
        $listener = new ApiAuthenticationListener($auth, $logger, $whitelist);
        return $listener;
    }
}
