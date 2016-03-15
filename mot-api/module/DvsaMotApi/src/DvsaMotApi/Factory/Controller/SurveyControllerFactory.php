<?php

namespace DvsaMotApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Controller\SurveyController;
use DvsaMotApi\Service\SurveyService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SurveyControllerFactory
 * @package DvsaMotApi\Factory
 */
class SurveyControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SurveyController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var ServiceManager $serviceLocator
         */
        $serviceLocator = $serviceLocator->getServiceLocator();

        /**
         * @var SurveyService $surveyService
         */
        $surveyService = new SurveyService(
            $serviceLocator->get(EntityManager::class)
        );
        
        return new SurveyController(
            $surveyService
        );
    }
}