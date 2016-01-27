<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModule\Factory\View;

use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGenerator;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating PersonProfileUrlGenerator.
 */
class PersonProfileUrlGeneratorFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return PersonProfileUrlGenerator
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var RouteStackInterface $router */
        $router = $serviceLocator->get('Router');

        /** @var Request $request */
        $request = $serviceLocator->get('Request');

        /** @var ContextProvider $contextProvider */
        $contextProvider = $serviceLocator->get(ContextProvider::class);

        return new PersonProfileUrlGenerator($router, $request, $contextProvider);
    }
}
