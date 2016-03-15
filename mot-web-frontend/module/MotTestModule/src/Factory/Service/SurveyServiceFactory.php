<?php

namespace Dvsa\Mot\Frontend\MotTestModule\Factory\Service;

use Dvsa\Mot\Frontend\MotTestModule\Service\SurveyService;
use DvsaCommon\HttpRestJson\Client;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SurveyServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SurveyService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SurveyService(
            $serviceLocator->get(Client::class)
        );
    }
}