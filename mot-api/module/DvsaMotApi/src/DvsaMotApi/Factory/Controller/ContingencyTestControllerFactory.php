<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace  DvsaMotApi\Factory\Controller;

use DvsaMotApi\Controller\ContingencyTestController;
use DvsaMotApi\Service\EmergencyService;
use DvsaMotApi\Validation\ContingencyTestValidator;
use SiteApi\Service\SiteService;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for ContingencyTestController instances.
 */
class ContingencyTestControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ContingencyTestController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ControllerManager $serviceLocator */
        $serviceLocator = $serviceLocator->getServiceLocator();

        /** @var EmergencyService $emergencyService */
        $emergencyService = $serviceLocator->get(EmergencyService::class);
        /** @var SiteService $siteService */
        $siteService = $serviceLocator->get(SiteService::class);

        $contingencyTestValidator = new ContingencyTestValidator($emergencyService, $siteService);

        return new ContingencyTestController($contingencyTestValidator);
    }
}
