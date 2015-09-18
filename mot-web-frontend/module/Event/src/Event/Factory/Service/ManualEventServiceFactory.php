<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Event\Factory\Service;

use Event\Service\ManualEventService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;

class ManualEventServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ManualEventService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ManualEventService(
            $serviceLocator->get(HttpRestJsonClient::class)
        );
    }
}
