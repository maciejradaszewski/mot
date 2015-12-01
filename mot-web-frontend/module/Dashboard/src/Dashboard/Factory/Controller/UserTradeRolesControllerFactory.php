<?php

namespace Dashboard\Factory\Controller;

use Core\Catalog\EnumCatalog;
use Core\DependancyInjection\AbstractFrontendControllerFactory;
use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Dashboard\Controller\UserTradeRolesController;
use Dashboard\Service\PersonTradeRoleSorterService;
use Dashboard\Service\TradeRolesAssociationsService;
use DvsaClient\MapperFactory;
use DvsaCommon\ApiClient\Person\PersonTradeRoles\PersonTradeRolesApiResource;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class UserTradeRolesControllerFactory extends AbstractFrontendControllerFactory
{
    public function createController(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        /** @var MapperFactory $mapperFactory */
        $mapperFactory = $serviceLocator->get(MapperFactory::class);

        return new UserTradeRolesController(
            $serviceLocator->get('MotIdentityProvider'),
            $serviceLocator->get(TradeRolesAssociationsService::class),
            $serviceLocator->get(ViewTradeRolesAssertion::class),
            $serviceLocator->get('AuthorisationService'),
            $mapperFactory->OrganisationPosition,
            $mapperFactory->SitePosition,
            $this->getApiResource(PersonTradeRolesApiResource::class),
            $serviceLocator->get(EnumCatalog::class),
            $serviceLocator->get('AuthorisationService'),
            $serviceLocator->get(PersonTradeRoleSorterService::class)
        );
    }
}
