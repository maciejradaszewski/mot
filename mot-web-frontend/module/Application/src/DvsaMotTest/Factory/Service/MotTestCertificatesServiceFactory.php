<?php

namespace DvsaMotTest\Factory\Service;

use Application\Service\MotTestCertificatesService;
use DvsaCommon\Configuration\MotConfig;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;

/**
 * Class MotTestCertificatesServiceFactory
 * @package DvsaMotTest\Factory\Service
 */
class MotTestCertificatesServiceFactory implements FactoryInterface
{

    /**
     * Create MotTestCertificatesService
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return MotTestCertificatesService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $restClient = $serviceLocator->get(HttpRestJsonClient::class);
        $config = $serviceLocator->get(MotConfig::class);
        $pageSize = $config->withDefault(20)->get('recent_certificate_list', 'page_size');

        return new MotTestCertificatesService($restClient, $pageSize);
    }
}
