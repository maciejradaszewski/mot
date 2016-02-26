<?php

namespace Application\View\HelperFactory;

use Application\View\Helper\ResourcesOnGovUkHelper;
use DvsaCommon\Utility\TypeCheck;
use OutOfBoundsException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for ResourcesOnGovUkFactoryHelper instances.
 */
class ResourcesOnGovUkFactory implements FactoryInterface
{
    const CONFIG_KEY = 'resources';

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ResourcesOnGovUkHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var \Zend\Mvc\Controller\ControllerManager $serviceLocator */
        $controllerManager = $serviceLocator->getServiceLocator();
        $config = $this->extractConfig($controllerManager->get('Config'));

        return new ResourcesOnGovUkHelper($config);
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function extractConfig(array $config)
    {
        if (!isset($config[self::CONFIG_KEY])) {
            throw new OutOfBoundsException(sprintf('Parameter "%s" is missing in configuration.', self::CONFIG_KEY));
        }

        TypeCheck::assertArray($config[self::CONFIG_KEY]);

        return $config[self::CONFIG_KEY];
    }
}
