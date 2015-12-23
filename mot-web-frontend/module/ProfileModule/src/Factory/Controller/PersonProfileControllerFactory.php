<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\ProfileModule\Factory\Controller;

use Application\Data\ApiPersonalDetails;
use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Dashboard\Data\ApiDashboardResource;
use Dashboard\PersonStore;
use Dashboard\Service\TradeRolesAssociationsService;
use Dvsa\Mot\Frontend\ProfileModule\Controller\PersonProfileController;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PersonProfileControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return PersonProfileController()
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new PersonProfileController(
            $serviceLocator->get('LoggedInUserManager'),
            $serviceLocator->get(ApiPersonalDetails::class),
            $serviceLocator->get(PersonStore::class),
            $serviceLocator->get(ApiDashboardResource::class),
            $serviceLocator->get('CatalogService'),
            $serviceLocator->get(UserAdminSessionManager::class),
            $serviceLocator->get(TesterGroupAuthorisationMapper::class),
            $serviceLocator->get('AuthorisationService'),
            $serviceLocator->get(UserAdminSessionManager::class),
            $serviceLocator->get(ViewTradeRolesAssertion::class),
            $serviceLocator->get(TradeRolesAssociationsService::class)
        );
    }
}
