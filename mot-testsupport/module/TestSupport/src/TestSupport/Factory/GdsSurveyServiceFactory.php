<?php

namespace TestSupport\Factory;

use Doctrine\ORM\EntityManager;
use TestSupport\Service\GdsSurveyService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GdsSurveyServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);
        $service = new GdsSurveyService($entityManager);
        return $service;
    }
}
