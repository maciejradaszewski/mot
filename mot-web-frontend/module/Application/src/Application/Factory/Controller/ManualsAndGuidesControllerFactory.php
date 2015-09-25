<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Application\Factory\Controller;

use Application\Controller\ManualsAndGuidesController;
use InvalidArgumentException;
use OutOfBoundsException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for ManualsAndGuidesController instances.
 */
class ManualsAndGuidesControllerFactory implements FactoryInterface
{
    const CONFIG_KEY = 'documents';

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ManualsAndGuidesController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var \Zend\Mvc\Controller\ControllerManager $serviceLocator */
        $controllerManager = $serviceLocator->getServiceLocator();

        $config = $this->extractConfig($controllerManager->get('Config'));

        return new ManualsAndGuidesController($config);
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

        if (!is_array($config[self::CONFIG_KEY])) {
            throw new InvalidArgumentException(sprintf('Parameter "%s" should be of array type, got "%s" instead',
                self::CONFIG_KEY, gettype(self::CONFIG_KEY)));
        }

        return $config[self::CONFIG_KEY];
    }
}
