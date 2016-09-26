<?php

namespace DvsaMotApi\Factory\Controller;

use DvsaCommon\Formatting\DefectSentenceCaseConverter;
use DvsaMotApi\Controller\MotTestReasonForRejectionController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;

/**
 * Class MotTestReasonForRejectionControllerFactory
 */
class MotTestReasonForRejectionControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return MotTestReasonForRejectionController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var ControllerManager $serviceLocator */
        $mainServiceLocator = $serviceLocator->getServiceLocator();
        $defectSentenceCaseConverter = $mainServiceLocator->get(DefectSentenceCaseConverter::class);

        return new MotTestReasonForRejectionController($defectSentenceCaseConverter);
    }
}
