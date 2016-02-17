<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\PersonModule\Factory\Controller;

use Application\Data\ApiPersonalDetails;
use Dvsa\Mot\Frontend\PersonModule\Controller\ChangeTelephoneController;
use Dvsa\Mot\Frontend\PersonModule\Security\PersonProfileGuardBuilder;
use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGenerator;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaClient\MapperFactory;
use UserAdmin\Service\HelpdeskAccountAdminService;
use UserAdmin\Service\PersonRoleManagementService;
use UserAdmin\Service\UserAdminSessionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ChangeTelephoneController Factory.
 */
class ChangeTelephoneControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return ChangeTelephoneController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        /** @var HelpdeskAccountAdminService $accountAdminService */
        $accountAdminService = $serviceLocator->get(HelpdeskAccountAdminService::class);

        /** @var TesterGroupAuthorisationMapper $authMapper */
        $authMapper = $serviceLocator->get(TesterGroupAuthorisationMapper::class);

        /** @var ContextProvider $contextProvider */
        $contextProvider = $serviceLocator->get(ContextProvider::class);

        /** @var UserAdminSessionService $sessionService */
        $sessionService = $serviceLocator->get(UserAdminSessionService::class);

        /** @var PersonRoleManagementService $personRoleManagementService */
        $personRoleManagementService = $serviceLocator->get(PersonRoleManagementService::class);

        /** @var PersonProfileUrlGenerator $personProfileUrlGenerator */
        $personProfileUrlGenerator = $serviceLocator->get(PersonProfileUrlGenerator::class);

        /** @var ApiPersonalDetails $personalDetailsService */
        $personalDetailsService = $serviceLocator->get(ApiPersonalDetails::class);

        /** @var PersonProfileGuardBuilder $personProfileGuardBuilder */
        $personProfileGuardBuilder = $serviceLocator->get(PersonProfileGuardBuilder::class);

        /** @var MapperFactory $mapperFactory */
        $mapperFactory = $serviceLocator->get(MapperFactory::class);

        return new ChangeTelephoneController(
            $accountAdminService,
            $authMapper,
            $contextProvider,
            $sessionService,
            $personRoleManagementService,
            $personProfileUrlGenerator,
            $personalDetailsService,
            $personProfileGuardBuilder,
            $mapperFactory
        );
    }
}
