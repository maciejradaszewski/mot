<?php

namespace DvsaMotApiTest\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceManager;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaMotApi\Service\AwsCredentialsProviderService;
use DvsaMotApi\Factory\AwsCredentialsProviderFactory;


class AwsCredentialsProviderFactoryTest extends AbstractServiceTestCase
{

    private $serviceLocator;

    public function setUp()
    {
        $config = [
            'aws' => []
        ];

        $config['aws']['certificateStorage']['secretKey'] = 'secretKey';
        $config['aws']['certificateStorage']['accessKeyId'] = 'accessKeyId';

        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService('Config', $config);
    }

    public function testFactory()
    {
        $service = (new AwsCredentialsProviderFactory())->createService($this->serviceLocator);

        $this->assertInstanceOf(
            AwsCredentialsProviderService::class,
            $service
        );
    }
}
