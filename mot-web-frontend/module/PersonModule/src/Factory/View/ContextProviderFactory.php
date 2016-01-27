<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModule\Factory\View;

use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating ContextProvider.
 */
class ContextProviderFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ContextProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var TreeRouteStack $router */
        $router = $serviceLocator->get('Router');

        /** @var Request $request */
        $request = $serviceLocator->get('Request');

        return new ContextProvider($router, $request);
    }
}
