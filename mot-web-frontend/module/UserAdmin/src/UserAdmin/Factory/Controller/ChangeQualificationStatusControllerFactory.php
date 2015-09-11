<?php

namespace UserAdmin\Factory\Controller;

use DvsaClient\MapperFactory;
use DvsaClient\Mapper\TesterGroupAuthorisationMapper;
use UserAdmin\Controller\ChangeQualificationStatusController;
use Zend\Db\Sql\Update;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;

/**
 * Factory for {@link \UserAdmin\Controller\UpdateQualificationController}
 */
class ChangeQualificationStatusControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceLocatorInterface $appServiceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        /** @var MapperFactory $mapperFactory */
        $mapperFactory = $serviceLocator->get(MapperFactory::class);

        /** @var HttpRestJsonClient $httpRestJsonClient */
        $httpRestJsonClient = $serviceLocator->get(HttpRestJsonClient::class);

        /** @var TesterGroupAuthorisationMapper $testerGtoupAuthorisationMapper */
        $testerGroupAuthorisationMapper = $serviceLocator->get(TesterGroupAuthorisationMapper::class);

        $controller = new ChangeQualificationStatusController(
            $serviceLocator->get('AuthorisationService'),
            new Container(ChangeQualificationStatusController::SESSION_CONTAINER_NAME),
            $mapperFactory->Person,
            $httpRestJsonClient,
            $testerGroupAuthorisationMapper
        );

        return $controller;
    }
}
