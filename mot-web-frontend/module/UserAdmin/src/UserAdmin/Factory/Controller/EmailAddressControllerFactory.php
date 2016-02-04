<?php

namespace UserAdmin\Factory\Controller;

use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use Dvsa\Mot\Frontend\PersonModule\View\PersonProfileUrlGenerator;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use DvsaClient\MapperFactory;
use UserAdmin\Controller\EmailAddressController;
use UserAdmin\Service\HelpdeskAccountAdminService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\EmailAddress;

/**
 * Factory for {@link \UserAdmin\Controller\EmailAddressController}.
 */
class EmailAddressControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $appServiceLocator = $controllerManager->getServiceLocator();

        $authorisationService = $appServiceLocator->get("AuthorisationService");
        $accountAdminService = $appServiceLocator->get(HelpdeskAccountAdminService::class);
        $testerGroupAuthorisationMapper = $appServiceLocator->get(TesterGroupAuthorisationMapper::class);

        /** @var MapperFactory $mapperFactory */
        $mapperFactory = $appServiceLocator->get(MapperFactory::class);

        /** @var PersonProfileUrlGenerator $personProfileUrlGenerator */
        $personProfileUrlGenerator = $appServiceLocator->get(PersonProfileUrlGenerator::class);

        /** @var ContextProvider $contextProvider */
        $contextProvider = $appServiceLocator->get(ContextProvider::class);

        $controller = new EmailAddressController(
            $authorisationService,
            $accountAdminService,
            $testerGroupAuthorisationMapper,
            $mapperFactory,
            $personProfileUrlGenerator,
            $contextProvider
        );

        return $controller;
    }
}
