<?php

namespace DvsaMotTest\Factory\Controller;

use Application\View\Helper\AuthorisationHelper;
use Core\Service\MotEventManager;
use DvsaMotTest\Controller\MotTestController;
use Zend\EventManager\EventManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
class MotTestControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MotTestController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        /** @var AuthorisationHelper $authService */
        $authService = $serviceLocator->get('authorisationHelper');

        $eventManager = $serviceLocator->get(MotEventManager::class);

        return new MotTestController($authService, $eventManager);
    }
}
