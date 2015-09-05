<?php

namespace DvsaMotApiTest\Factory;

use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Factory\AmazonSDKServiceFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\AmazonSDKService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceManager;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaMotApi\Service\AwsCredentialsProviderService;


class AmazonSDKServiceFactoryTest extends AbstractServiceTestCase
{

    private $serviceLocator;

    public function setUp()
    {
        $config = [
            'aws' => []
        ];

        $config['aws']['certificateStorage']['secretKey'] = 'secretKey';
        $config['aws']['certificateStorage']['accessKeyId'] = 'accessKeyId';

        $credentials = XMock::of(AwsCredentialsProviderService::class);

        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService('Config', $config);
        $this->serviceLocator->setService(AwsCredentialsProviderService::class, $credentials);
    }

    public function testFactory()
    {
        $service = (new AmazonSDKServiceFactory())->createService($this->serviceLocator);

        $this->assertInstanceOf(
            AmazonSDKService::class,
            $service
        );
    }
}
