<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\OpenAM\Response;

use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailureBuilder;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for OpenAMAuthenticator instances.
 */
class OpenAMAuthFailureBuilderFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @throws RuntimeException If helpdesk details are not found in the configuration.
     *
     * @return OpenAMAuthFailureBuilder
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {   /** @var OpenAMClientOptions $openAMClientOptions */
        $openAMClientOptions = $serviceLocator->get(OpenAMClientOptions::class);

        $config = $serviceLocator->get('Config');
        $helpdeskConfig = isset($config['helpdesk']) ? $config['helpdesk'] : null;
        if (!$helpdeskConfig) {
            throw new RuntimeException('Helpdesk details not found in $config["helpdesk"]');
        }

        return new OpenAMAuthFailureBuilder($openAMClientOptions, $helpdeskConfig);
    }
}
