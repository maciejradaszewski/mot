<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Factory\Service;

use Dvsa\Mot\Api\RegistrationModule\Service\OpenAMIdentityCreator;
use Dvsa\OpenAM\OpenAMClient;
use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OpenAMIdentityCreatorFactory.
 */
class OpenAMIdentityCreatorFactory implements FactoryInterface
{
    /**
     * Create service.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return OpenAMIdentityCreator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var OpenAMClient $openAMClient */
        $openAMClient = $serviceLocator->get(OpenAMClientInterface::class);

        /** @var OpenAMClientOptions $OpenAMClientOptions */
        $OpenAMClientOptions = $serviceLocator->get(OpenAMClientOptions::class);

        $service = new OpenAMIdentityCreator(
            $openAMClient,
            $OpenAMClientOptions
        );

        return $service;
    }
}
