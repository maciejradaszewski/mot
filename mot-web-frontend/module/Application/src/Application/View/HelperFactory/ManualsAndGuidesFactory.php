<?php

namespace Application\View\HelperFactory;

use Application\View\Helper\ManualsAndGuidesHelper;
use DvsaCommon\Utility\TypeCheck;
use OutOfBoundsException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for ManualsAndGuidesHelper instances.
 */
class ManualsAndGuidesFactory implements FactoryInterface
{
    const CONFIG_KEY = 'manuals';

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ManualsAndGuidesHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var \Zend\Mvc\Controller\ControllerManager $serviceLocator */
        $controllerManager = $serviceLocator->getServiceLocator();
        $config = $this->extractConfig($controllerManager->get('Config'));

        return new ManualsAndGuidesHelper($config);
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
