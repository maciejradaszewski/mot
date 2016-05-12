<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dashboard\Factory\Controller;

use Core\Catalog\EnumCatalog;
use Core\DependancyInjection\AbstractFrontendControllerFactory;
use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Dashboard\Controller\UserTradeRolesController;
use Dashboard\Service\PersonTradeRoleSorterService;
use Dashboard\Service\TradeRolesAssociationsService;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGenerator;
use DvsaClient\MapperFactory;
use DvsaCommon\ApiClient\Person\PersonTradeRoles\PersonTradeRolesApiResource;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;

/**
 * Class UserTradeRolesControllerFactory.
 */
class UserTradeRolesControllerFactory extends AbstractFrontendControllerFactory
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $controllerManager
     *
     * @return \Dashboard\Controller\UserTradeRolesController
     */
    public function createController(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        /** @var MapperFactory $mapperFactory */
        $mapperFactory = $serviceLocator->get(MapperFactory::class);

        /** @var PersonProfileUrlGenerator $personProfileUrlGenerator */
        $personProfileUrlGenerator = $serviceLocator->get(PersonProfileUrlGenerator::class);

        /** @var ContextProvider $contextProvider */
        $contextProvider = $serviceLocator->get(ContextProvider::class);

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
            $serviceLocator->get(PersonTradeRoleSorterService::class),
            $personProfileUrlGenerator,
            $contextProvider
        );
    }
}
