<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModule\Factory\View;

use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGenerator;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGeneratorViewHelper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for creating PersonProfileUrlGeneratorViewHelper.
 */
class PersonProfileUrlGeneratorViewHelperFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return PersonProfileUrlGeneratorViewHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        /** @var PersonProfileUrlGenerator $personProfileUrlGenerator */
        $personProfileUrlGenerator = $parentLocator->get(PersonProfileUrlGenerator::class);

        /** @var ContextProvider $contextProvider */
        $contextProvider = $parentLocator->get(ContextProvider::class);

        return new PersonProfileUrlGeneratorViewHelper($personProfileUrlGenerator, $contextProvider);
    }
}
