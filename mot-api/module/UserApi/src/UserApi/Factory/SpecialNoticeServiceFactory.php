<?php

namespace UserApi\Factory;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Date\DateTimeHolder;
use UserApi\SpecialNotice\Service\SpecialNoticeService;
use UserApi\SpecialNotice\Service\Validator\SpecialNoticeValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SpecialNoticeServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SpecialNoticeService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('Hydrator'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('DvsaAuthenticationService'),
            new SpecialNoticeValidator(),
            new DateTimeHolder()
        );
    }
}
