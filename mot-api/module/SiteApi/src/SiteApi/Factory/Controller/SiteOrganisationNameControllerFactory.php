<?php

namespace SiteApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use OrganisationApi\Service\OrganisationService;
use SiteApi\Controller\SiteNameController;
use SiteApi\Controller\SiteOrganisationNameController;
use SiteApi\Service\SiteService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class SiteOrganisationNameControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new SiteOrganisationNameController(
            $serviceLocator->get(OrganisationService::class)
        );
    }
}
