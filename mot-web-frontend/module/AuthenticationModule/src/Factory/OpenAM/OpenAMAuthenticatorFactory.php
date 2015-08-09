<?php

/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\OpenAM;

use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\OpenAMAuthenticator;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailureBuilder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for OpenAMAuthenticator instances.
 */
class OpenAMAuthenticatorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return OpenAMAuthenticator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $serviceLocator->get(OpenAMClientOptions::class);
        $client = $serviceLocator->get(OpenAMClientInterface::class);
        /** @var OpenAMAuthFailureBuilder $authFailureBuilder */
        $authFailureBuilder = $serviceLocator->get(OpenAMAuthFailureBuilder::class);
        $logger = $serviceLocator->get('Application\Logger');

        return new OpenAMAuthenticator($client, $options, $authFailureBuilder, $logger);
    }
}
